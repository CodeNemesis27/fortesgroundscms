<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Concerns\RegistersPdfFonts;
use App\Filament\Resources\Projects\ProjectResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProject extends ViewRecord
{
    use RegistersPdfFonts;

    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('printPdf')
                ->label('Print PDF')
                ->tooltip('Generate a PDF of this project, including its contracts and schedule tasks')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->action(function () {
                    $project = $this->record->load([
                        'client',
                        'contracts',
                        'scheduleTasks.employee.user',
                    ]);

                    $pdf = Pdf::loadView('pdf.project-details', [
                        'project' => $project,
                    ])->setPaper('a4', 'portrait');

                    $this->registerPdfFonts($pdf);

                    return response()->streamDownload(
                        fn() => print($pdf->output()),
                        "{$project->project_code}-details.pdf"
                    );
                }),
            EditAction::make(),
        ];
    }

    public function getHeading(): string
    {
        return 'View ' . $this->record->name;
    }
}
