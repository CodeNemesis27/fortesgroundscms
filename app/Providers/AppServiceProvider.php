<?php

namespace App\Providers;

use App\Http\Responses\LogoutResponse;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    // public array $singletons = [
    //     LogoutResponseContract::class => LogoutResponse::class,
    // ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

// use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;
// use App\Http\Responses\LogoutResponse;
