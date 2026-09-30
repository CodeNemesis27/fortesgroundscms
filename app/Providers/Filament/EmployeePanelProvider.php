<?php

namespace App\Providers\Filament;

use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use FilamentInbox\FilamentInboxPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Martin6363\SidebarResize\SidebarResizePlugin;

class EmployeePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('employee')
            ->path('employee')
            ->colors([
                'primary' => Color::Red,
            ])
            ->font('Geist')
            ->brandName('FortesGrounds')
            ->multiFactorAuthentication([
                AppAuthentication::make()
                    ->recoverable()
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Project Management'),
                NavigationGroup::make()
                    ->label('Procurement'),
                NavigationGroup::make()
                    ->label('Document Management'),
                NavigationGroup::make()
                    ->label('Messages'),
            ])
            ->plugins([
                FilamentInboxPlugin::make(),
                SidebarResizePlugin::make()
                    ->minWidth(260)
                    ->maxWidth(320)
            ])
            ->discoverResources(in: app_path('Filament/Employee/Resources'), for: 'App\Filament\Employee\Resources')
            ->discoverPages(in: app_path('Filament/Employee/Pages'), for: 'App\Filament\Employee\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Employee/Widgets'), for: 'App\Filament\Employee\Widgets')
            ->widgets([
                //
            ])
            ->renderHook(PanelsRenderHook::TOPBAR_BEFORE, fn() => view('filament.announcement-banner'))
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                'role:Employee',
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css');
    }
}
