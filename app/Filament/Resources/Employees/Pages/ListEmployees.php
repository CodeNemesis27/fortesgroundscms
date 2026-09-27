<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Concerns\RegistersPdfFonts;
use App\Filament\Resources\Employees\EmployeeResource;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;

class ListEmployees extends ListRecords
{
    use RegistersPdfFonts;

    protected static string $resource = EmployeeResource::class;

    protected ?string $subheading = 'Manage employee records, roles, and employment status';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color(Color::Red)
                ->tooltip('Generate a printable PDF listing all employees')
                ->action(function () {
                    $employees = Employee::all();

                    $pdf = Pdf::loadView('pdf.employees-list', [
                        'employees' => $employees,
                    ])->setPaper('a4', 'landscape');

                    $this->registerPdfFonts($pdf);

                    return response()->streamDownload(
                        fn() => print($pdf->output()),
                        'employees-' . now()->format('Y-m-d') . '.pdf'
                    );
                }),

            CreateAction::make(),
        ];
    }
}
