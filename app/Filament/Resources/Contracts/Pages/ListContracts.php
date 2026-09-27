<?php

namespace App\Filament\Resources\Contracts\Pages;

use App\Filament\Concerns\RegistersPdfFonts;
use App\Filament\Resources\Contracts\ContractResource;
use App\Filament\Resources\Contracts\Widgets\ContractResourceStat;
use App\Models\Contract;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Builder;

class ListContracts extends ListRecords
{
    use RegistersPdfFonts;

    protected static string $resource = ContractResource::class;

    protected ?string $subheading = 'Manage project contracts, values, and approval status';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color(Color::Red)
                ->tooltip('Generate a printable PDF listing all contracts')
                ->action(function () {
                    $contracts = Contract::with('project')->get();

                    $pdf = Pdf::loadView('pdf.contracts-list', [
                        'contracts' => $contracts,
                    ])->setPaper('a4', 'landscape');

                    $this->registerPdfFonts($pdf);

                    return response()->streamDownload(
                        fn() => print($pdf->output()),
                        'contracts-' . now()->format('Y-m-d') . '.pdf'
                    );
                }),
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make()
                ->badge(Contract::query()->count()),
            'Draft' => Tab::make()
                ->badge(Contract::query()->where('status', 'Draft')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Draft')),
            'Active' => Tab::make()
                ->badge(Contract::query()->where('status', 'Active')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Active')),
            'Completed' => Tab::make()
                ->badge(Contract::query()->where('status', 'Completed')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Completed')),
            'Terminated' => Tab::make()
                ->badge(Contract::query()->where('status', 'Terminated')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Terminated')),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ContractResourceStat::class,
        ];
    }
}
