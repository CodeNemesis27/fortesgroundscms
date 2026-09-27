<?php

namespace App\Filament\Resources\ScheduleTasks\Pages;

use App\Filament\Resources\ScheduleTasks\ScheduleTaskResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewScheduleTask extends ViewRecord
{
    protected static string $resource = ScheduleTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getHeading(): string
    {
        return 'View ' . $this->record->name;
    }
}
