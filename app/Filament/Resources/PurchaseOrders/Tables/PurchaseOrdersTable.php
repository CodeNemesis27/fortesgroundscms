<?php

namespace App\Filament\Resources\PurchaseOrders\Tables;

use App\Filament\Concerns\RegistersPdfFonts;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\PurchaseOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ColumnManagerLayout;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PurchaseOrdersTable
{
    use RegistersPdfFonts;

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('purchase_order_no')
                    ->searchable()
                    ->label('Purchase order no.'),
                TextColumn::make('project.name')
                    ->label('Project reference')
                    ->sortable()
                    ->color('info')
                    ->extraAttributes(['class' => 'hover:underline'])
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->iconColor('info')
                    ->iconPosition(IconPosition::After)
                    ->url(fn($record) => ProjectResource::getUrl('view', ['record' => $record->project]))
                    ->openUrlInNewTab(),
                TextColumn::make('expected_delivery_date')
                    ->date()
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->sortable(),
                TextColumn::make('actual_delivery_date')
                    ->date()
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('grand_total')
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('approved_date')
                    ->date()
                    ->placeholder('-')
                    ->icon(Heroicon::OutlinedCalendarDays),
                TextColumn::make('created_at')
                    ->since()
                    ->dateTimeTooltip('M d, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->since()
                    ->dateTimeTooltip('M d, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->since()
                    ->dateTimeTooltip('M d, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('approved_date')
                    ->label('Approval Status')
                    ->placeholder('All')
                    ->trueLabel('Approved')
                    ->falseLabel('Not Approved')
                    ->queries(
                        true: fn(Builder $query) => $query->whereNotNull('approved_date'),
                        false: fn(Builder $query) => $query->whereNull('approved_date'),
                        blank: fn(Builder $query) => $query,
                    ),
                TrashedFilter::make()
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    Action::make('exportMaterials')
                        ->label('Export materials')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('gray')
                        ->tooltip('Generate a PDF of the materials purchased in this purchase order')
                        ->action(function (PurchaseOrder $record) {
                            $purchaseOrder = $record->load(['project', 'purchaseOrderItems.material.vendor']);

                            $pdf = Pdf::loadView('pdf.purchase-order-materials', [
                                'purchaseOrder' => $purchaseOrder,
                            ])->setPaper('a4', 'portrait');



                            return response()->streamDownload(
                                fn() => print($pdf->output()),
                                "{$purchaseOrder->purchase_order_no}-materials.pdf"
                            );
                        }),
                ])
            ])
            ->toolbarActions([
                //
            ])
            ->striped()
            ->columnManagerLayout(ColumnManagerLayout::Modal)
            ->columnManagerTriggerAction(
                fn(Action $action) => $action
                    ->slideOver()
                    ->label('Columns')
                    ->button()
            )
            ->filtersLayout(FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->slideOver()
                    ->button()
                    ->label('Filters')
            )
            ->defaultSort('created_at', 'desc');
    }
}
