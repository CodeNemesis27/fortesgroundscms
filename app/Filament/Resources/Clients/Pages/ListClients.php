<?php

namespace App\Filament\Resources\Clients\Pages;

use App\Filament\Concerns\RegistersPdfFonts;
use App\Filament\Resources\Clients\ClientResource;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;

class ListClients extends ListRecords
{
    use RegistersPdfFonts;

    protected static string $resource = ClientResource::class;

    protected ?string $subheading = 'View client information and contact details';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('previewPdf')
                ->label('Preview PDF')
                ->color('gray')
                ->icon('heroicon-o-eye')
                ->tooltip('Preview the PDF layout before exporting')
                ->modalHeading('Clients PDF Preview')
                ->modalWidth(Width::FourExtraLarge)
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close')
                ->modalContent(function () {
                    $pdf = $this->buildClientsPdf();

                    return view('filament.modals.pdf-preview', [
                        'base64' => base64_encode($pdf->output()),
                    ]);
                }),
            Action::make('exportPdf')
                ->label('Export PDF')
                ->color(Color::Red)
                ->icon('heroicon-o-document-arrow-down')
                ->tooltip('Generate a printable PDF listing all clients')
                ->action(function () {
                    $clients = Client::with('user')->get();

                    $pdf = Pdf::loadView('pdf.clients-list', [
                        'clients' => $clients,
                    ])->setPaper('a4', 'portrait');

                    $this->registerPdfFonts($pdf);

                    return response()->streamDownload(
                        fn() => print($pdf->output()),
                        'clients-' . now()->format('Y-m-d') . '.pdf'
                    );
                }),
        ];
    }

    protected function buildClientsPdf()
    {
        $clients = Client::with('user')->get();

        $pdf = Pdf::loadView('pdf.clients-list', [
            'clients' => $clients,
        ])->setPaper('a4', 'portrait');

        $this->registerPdfFonts($pdf);

        return $pdf;
    }
}
