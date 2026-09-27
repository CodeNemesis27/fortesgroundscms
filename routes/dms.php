<?php

use App\Http\Controllers\DocumentShareLinkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Document sharing routes
|--------------------------------------------------------------------------
|
| Include this file from routes/web.php:
|
|   require __DIR__.'/dms.php';
|
| Rate-limited to slow down token brute-forcing / password guessing on
| link shares. Tokens are 64 characters of high-entropy randomness, so
| brute force is already infeasible, but throttling is cheap insurance.
|
*/

Route::middleware(['web', 'throttle:30,1'])->group(function () {
    Route::get('/share/{token}', [DocumentShareLinkController::class, 'show'])
        ->name('dms.share.show');

    Route::post('/share/{token}/download', [DocumentShareLinkController::class, 'download'])
        ->name('dms.share.download');
});
