<?php

namespace Elsayed85\FilamentCountrySwitch\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class FilamentCountrySwitch extends Component
{
    public function changeLocale($locale)
    {
        session()->put('filament_country', $locale);

        cookie()->queue(cookie()->forever('filament_country', $locale));

        $this->dispatch('filament-country-changed');

        $this->redirect(request()->header('Referer'));

    }

    public function render(): View
    {
        return view('filament-country-switch::country-switch');
    }
}
