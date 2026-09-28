<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use Awcodes\LightSwitch\Enums\Alignment;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use FilamentInbox\FilamentInboxPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Martin6363\SidebarResize\SidebarResizePlugin;
use Openplain\FilamentShadcnTheme\Color;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->font('Onest')
            ->profile()
            ->colors([
                'primary' => Color::Default
            ])
            ->multiFactorAuthentication([
                AppAuthentication::make()
                    ->recoverable()
            ])
            ->databaseNotifications()
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Project Management'),
                NavigationGroup::make()
                    ->label('Procurement'),
                NavigationGroup::make()
                    ->label('Document Management'),
                NavigationGroup::make()
                    ->label('Messages'),
                NavigationGroup::make()
                    ->label('System Management'),
            ])
            ->plugins([
                FilamentInboxPlugin::make(),
                SidebarResizePlugin::make()
                    ->minWidth(260)
                    ->maxWidth(320),
                FilamentSpatieLaravelHealthPlugin::make()
                    ->navigationGroup('System Management')
                    ->navigationIcon('heroicon-s-heart')
            ])
            ->renderHook(PanelsRenderHook::TOPBAR_BEFORE, fn() => view('filament.announcement-banner'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
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
                'role:Admin',
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
