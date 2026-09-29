<?php

namespace App\Providers;

use App\Http\Responses\LogoutResponse;
use F9Web\Health\Checks\OpCacheCheck;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Support\ServiceProvider;
use Spatie\CpuLoadHealthCheck\CpuLoadCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DatabaseConnectionCountCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\QueueCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;
use Spatie\SecurityAdvisoriesHealthCheck\SecurityAdvisoriesCheck;
use Ziming\LaravelDomainHealthCheck\DomainCheck;
use Ziming\LaravelMemoryHealthCheck\UsedMemoryCheck;

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
        Health::checks([
            OptimizedAppCheck::new(),
            DebugModeCheck::new(),
            EnvironmentCheck::new(),
            CacheCheck::new(),
            CpuLoadCheck::new()
                ->failWhenLoadIsHigherInTheLast5Minutes(2.0)
                ->failWhenLoadIsHigherInTheLast15Minutes(1.5),
            DatabaseCheck::new(),
            DatabaseConnectionCountCheck::new()
                ->warnWhenMoreConnectionsThan(50)
                ->failWhenMoreConnectionsThan(100),
            PingCheck::new()->url('https://fortesgroundscms-production-xewgem.laravel.cloud'),
            QueueCheck::new(),
            ScheduleCheck::new(),
            SecurityAdvisoriesCheck::new(),
            UsedDiskSpaceCheck::new()
                ->warnWhenUsedSpaceIsAbovePercentage(60)
                ->failWhenUsedSpaceIsAbovePercentage(80),
            UsedMemoryCheck::new()
                ->warnWhenUsedMemoryIsAbovePercentage(85)
                ->failWhenUsedMemoryIsAbovePercentage(95),
            OpCacheCheck::new(),
        ]);
    }
}
