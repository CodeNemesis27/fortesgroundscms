<?php

namespace App\Filament\Resources\ScheduleTasks\Pages;

use App\Filament\Resources\ScheduleTasks\ScheduleTaskResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditScheduleTask extends EditRecord
{
    protected static string $resource = ScheduleTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
