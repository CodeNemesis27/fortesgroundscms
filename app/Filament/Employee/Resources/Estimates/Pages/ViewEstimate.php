<?php

namespace App\Filament\Employee\Resources\Estimates\Pages;

use App\Filament\Employee\Resources\Estimates\EstimateResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEstimate extends ViewRecord
{
    protected static string $resource = EstimateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getHeading(): string
    {
        return 'View ' . $this->record->estimate_no;
    }
}
