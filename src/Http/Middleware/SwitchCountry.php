<?php

namespace Elsayed85\FilamentCountrySwitch\Http\Middleware;

use Elsayed85\FilamentCountrySwitch\CountrySwitch;
use Closure;
use Illuminate\Http\Request;

class SwitchCountry
{
    public function handle(Request $request, Closure $next): \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
    {
        $country = session()->get('filament_country')
            ?? $request->get('filament_country')
            ?? $request->cookie('filament_country');

        if (in_array($country, CountrySwitch::make()->getCountries())) {
            session()->put('country', $country);
        }

        return $next($request);
    }
}
