<?php

namespace Elsayed85\FilamentCountrySwitch;

use Elsayed85\FilamentCountrySwitch\Http\Livewire\FilamentCountrySwitch;
use Elsayed85\FilamentCountrySwitch\Http\Middleware\SwitchCountry;
use Filament\Facades\Filament;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentCountrySwitchServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-country-switch';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews();
    }

    public function packageBooted(): void
    {
        $this->registerPluginMiddleware();

        Livewire::component('filament-country-switch', FilamentCountrySwitch::class);

        FilamentAsset::register(
            assets: [
                Css::make('filament-country-switch', __DIR__ . '/../resources/dist/filament-country-switch.css'),
            ],
            package: 'elsayed85/country-switch'
        );

        Filament::serving(function () {
            CountrySwitch::boot();
        });
    }

    public function registerPluginMiddleware(): void
    {
        collect(CountrySwitch::make()->getPanels())
            ->each(fn ($panel) => $this->reorderCurrentPanelMiddlewareStack($panel));
    }

    protected function reorderCurrentPanelMiddlewareStack(Panel $panel): void
    {
        $middlewareStack = invade($panel)->getMiddleware();

        $middleware = SwitchCountry::class;
        $order = 'before';
        $referenceMiddleware = DispatchServingFilamentEvent::class;

        $middleware = is_array($middleware) ? collect($middleware) : collect([$middleware]);

        $middlewareCollection = collect($middlewareStack);

        $referenceIndex = $middlewareCollection->search($referenceMiddleware);
        $position = $order === 'before' ? $referenceIndex : $referenceIndex + 1;
        $position = $referenceMiddleware === null || $referenceIndex === false ? ($order === 'after' ? $middlewareCollection->count() : 0) : $position;

        invade($panel)->middleware = $middlewareCollection
            ->slice(0, $position)
            ->concat($middleware)
            ->concat($middlewareCollection->slice($position))
            ->toArray();
    }
}
