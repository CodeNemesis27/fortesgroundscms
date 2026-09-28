<?php

namespace App\Filament\Resources\ScheduleTasks;

use App\Filament\Resources\ScheduleTasks\Pages\CreateScheduleTask;
use App\Filament\Resources\ScheduleTasks\Pages\EditScheduleTask;
use App\Filament\Resources\ScheduleTasks\Pages\ListScheduleTasks;
use App\Filament\Resources\ScheduleTasks\Pages\ViewScheduleTask;
use App\Filament\Resources\ScheduleTasks\Schemas\ScheduleTaskForm;
use App\Filament\Resources\ScheduleTasks\Schemas\ScheduleTaskInfolist;
use App\Filament\Resources\ScheduleTasks\Tables\ScheduleTasksTable;
use App\Models\ScheduleTask;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ScheduleTaskResource extends Resource
{
    protected static ?string $model = ScheduleTask::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static string | UnitEnum | null $navigationGroup = 'Project Management';

    public static function form(Schema $schema): Schema
    {
        return ScheduleTaskForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ScheduleTaskInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScheduleTasksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListScheduleTasks::route('/'),
            'create' => CreateScheduleTask::route('/create'),
            'view' => ViewScheduleTask::route('/{record}'),
            // 'edit' => EditScheduleTask::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
