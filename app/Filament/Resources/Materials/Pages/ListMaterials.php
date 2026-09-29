<?php

namespace App\Filament\Resources\Materials\Pages;

use App\Filament\Concerns\RegistersPdfFonts;
use App\Filament\Resources\Materials\MaterialResource;
use App\Models\Material;
use Barryvdh\DomPDF\Facade\Pdf;
use BokshornIt\FilamentActivityTimeline\Actions\ActivityTimelineAction;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;

class ListMaterials extends ListRecords
{
    use RegistersPdfFonts;

    protected static string $resource = MaterialResource::class;

    protected ?string $subheading = 'Browse materials, pricing, and vendor sources';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color(Color::Red)
                ->tooltip('Generate a printable PDF listing all materials')
                ->action(function () {
                    $materials = Material::with('vendor')->get();

                    $pdf = Pdf::loadView('pdf.materials-list', [
                        'materials' => $materials,
                    ])->setPaper('a4', 'portrait');

                    $this->registerPdfFonts($pdf);

                    return response()->streamDownload(
                        fn() => print($pdf->output()),
                        'materials-' . now()->format('Y-m-d') . '.pdf'
                    );
                }),

            CreateAction::make(),
        ];
    }
}
