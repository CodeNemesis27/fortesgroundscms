<?php

namespace App\Filament\Employee\Resources\Documents\RelationManagers;

use App\Enums\AuditEvent;
use App\Enums\DocumentPermission;
use App\Models\DocumentShare;
use App\Services\DocumentAuditLogger;
use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class SharesRelationManager extends RelationManager
{
    protected static string $relationship = 'shares';

    protected static ?string $title = 'Sharing & access';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('share_type')
                            ->label('Share with')
                            ->options([
                                'user' => 'A specific system user',
                                'link' => 'A secure external link',
                            ])
                            ->default('user')
                            ->live()
                            ->dehydrated(false)
                            ->required(),

                        Select::make('shared_with_user_id')
                            ->label('User')
                            ->relationship('sharedWith', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn($get) => $get('share_type') === 'user')
                            ->required(fn($get) => $get('share_type') === 'user'),

                        Select::make('permission')
                            ->options(collect(DocumentPermission::cases())->mapWithKeys(
                                fn(DocumentPermission $case) => [$case->value => $case->label()]
                            ))
                            ->default(DocumentPermission::View->value)
                            ->required(),

                        TextInput::make('link_password')
                            ->label('Link password (optional)')
                            ->password()
                            ->revealable()
                            ->visible(fn($get) => $get('share_type') === 'link'),

                        TextInput::make('max_downloads')
                            ->numeric()
                            ->minValue(1)
                            ->visible(fn($get) => $get('share_type') === 'link'),

                        DateTimePicker::make('expires_at')
                            ->label('Expires at')
                            ->default(fn() => now()->addHours((int) config('dms.share_link.default_expiry_hours'))),
                    ])->columns(2)

            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('sharedWith.name')
                    ->label('Shared with')
                    ->placeholder('External link'),
                TextColumn::make('permission')
                    ->badge()
                    ->formatStateUsing(fn(DocumentPermission $state) => $state->label()),
                TextColumn::make('sharedBy.name')->label('Shared by'),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->state(fn(DocumentShare $record) => $record->isUsable())
                    ->boolean(),
                TextColumn::make('expires_at')
                    ->since()
                    ->dateTimeTooltip('M d, Y h:i A')
                    ->placeholder('Never'),
                TextColumn::make('download_count')
                    ->label('Downloads'),
                TextColumn::make('last_accessed_at')
                    ->since()
                    ->dateTimeTooltip('M d, Y h:i A')
                    ->placeholder('Never')
                    ->label('Last accessed'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['shared_by'] = auth()->id();

                        if (! empty($data['link_password'])) {
                            $data['password_hash'] = Hash::make($data['link_password']);
                        }
                        unset($data['link_password']);

                        return $data;
                    })
                    ->after(function (DocumentShare $record) {
                        app(DocumentAuditLogger::class)->log(
                            AuditEvent::Shared,
                            $record->document,
                            [
                                'share_id' => $record->id,
                                'shared_with_user_id' => $record->shared_with_user_id,
                                'is_link_share' => $record->shared_with_user_id === null,
                                'permission' => $record->permission->value,
                            ]
                        );

                        if ($record->shared_with_user_id === null) {
                            Notification::make()
                                ->title('Share link created')
                                ->body(url("/share/{$record->token}"))
                                ->success()
                                ->persistent()
                                ->send();
                        }
                    }),
            ])
            ->recordActions([
                Action::make('copyLink')
                    ->label('Open link')
                    ->icon('heroicon-o-link')
                    ->visible(fn(DocumentShare $record) => $record->token !== null && $record->isUsable())
                    ->url(fn(DocumentShare $record) => url("/share/{$record->token}"))
                    ->openUrlInNewTab(),

                Action::make('revoke')
                    ->label('Revoke')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn(DocumentShare $record) => $record->isUsable())
                    ->action(function (DocumentShare $record) {
                        $record->update(['revoked_at' => now()]);

                        app(DocumentAuditLogger::class)->log(
                            AuditEvent::ShareRevoked,
                            $record->document,
                            ['share_id' => $record->id]
                        );
                    }),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
