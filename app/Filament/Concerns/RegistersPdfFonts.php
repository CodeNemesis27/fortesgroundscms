<?php

namespace App\Filament\Concerns;

trait RegistersPdfFonts
{
    protected function registerPdfFonts($pdf): void
    {
        $fontCachePath = storage_path('fonts');

        if (! is_dir($fontCachePath)) {
            mkdir($fontCachePath, 0755, true);
        }

        $fontMetrics = $pdf->getDomPDF()->getFontMetrics();

        $fontMetrics->registerFont(
            ['family' => 'Onest', 'style' => 'normal', 'weight' => 'normal'],
            resource_path('fonts/onest/Onest-Regular.ttf')
        );

        $fontMetrics->registerFont(
            ['family' => 'Onest', 'style' => 'normal', 'weight' => 'bold'],
            resource_path('fonts/onest/Onest-Bold.ttf')
        );
    }
}
