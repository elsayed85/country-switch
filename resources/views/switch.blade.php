<x-filament::dropdown
    teleport
    :placement="$placement"
    :width="$isFlagsOnly ? 'flags-only' : 'fls-dropdown-width'"
    class="fi-dropdown fi-user-menu"
>
    <x-slot name="trigger">
        <div
            @class([
                'flex items-center justify-center w-9 h-9 language-switch-trigger text-primary-600 bg-primary-500/10',
                'rounded-full' => $isCircular,
                'rounded-lg' => !$isCircular,
                'p-1 ring-2 ring-inset ring-gray-200 hover:ring-gray-300 dark:ring-gray-500 hover:dark:ring-gray-400' => $isFlagsOnly || $hasFlags,
            ])
            x-tooltip="{
                content: @js($countrySwitch->getLabel(session('filament_country' , 'kw'))),
                theme: $store.theme,
                placement: 'bottom'
            }"
        >
            @if ($isFlagsOnly || $hasFlags)
                <x-filament-country-switch::flag
                    :src="$countrySwitch->getFlag(session('filament_country' , 'kw'))"
                    :circular="$isCircular"
                    :alt="$countrySwitch->getLabel(session('filament_country' , 'kw'))"
                    :switch="true"
                />
            @else
                <span class="font-semibold text-md">{{ $countrySwitch->getCharAvatar(session('filament_country' , 'kw')) }}</span>
            @endif
        </div>
    </x-slot>

    <x-filament::dropdown.list @class(['!border-t-0 space-y-1 !p-2.5'])>
        @foreach ($countries as $country)
            @if(session('filament_country') != $country)
                <button
                    type="button"
                    wire:click="changeLocale('{{ $country }}')"
                    @if ($isFlagsOnly)
                        x-tooltip="{
                        content: @js($countrySwitch->getLabel($country)),
                        theme: $store.theme,
                        placement: 'right'
                    }"
                    @endif

                    @class([
                        'flex items-center w-full transition-colors duration-75 rounded-md outline-none fi-dropdown-list-item whitespace-nowrap disabled:pointer-events-none disabled:opacity-70 fi-dropdown-list-item-color-gray hover:bg-gray-950/5 focus:bg-gray-950/5 dark:hover:bg-white/5 dark:focus:bg-white/5',
                        'justify-center px-2 py-0.5' => $isFlagsOnly,
                        'justify-start space-x-2 rtl:space-x-reverse p-1' => !$isFlagsOnly,
                    ])
                >

                    @if ($isFlagsOnly)
                        <x-filament-country-switch::flag
                            :src="$countrySwitch->getFlag($country)"
                            :circular="$isCircular"
                            :alt="$countrySwitch->getLabel($country)"
                            class="w-7 h-7"
                        />
                    @else
                        @if ($hasFlags)
                            <x-filament-country-switch::flag
                                :src="$countrySwitch->getFlag($country)"
                                :circular="$isCircular"
                                :alt="$countrySwitch->getLabel($country)"
                                class="p-0.5 w-7 h-7"
                            />
                        @else
                            <span
                                @class([
                                    'flex items-center justify-center flex-shrink-0 w-7 h-7 p-2 text-xs font-semibold group-hover:bg-white group-hover:text-primary-600 group-hover:border group-hover:border-primary-500/10 group-focus:text-white bg-primary-500/10 text-primary-600',
                                    'rounded-full' => $isCircular,
                                    'rounded-lg' => !$isCircular,
                                ])
                            >
                                {{ $countrySwitch->getCharAvatar($country) }}
                            </span>
                        @endif
                        <span class="text-sm font-medium text-gray-600 hover:bg-transparent dark:text-gray-200">
                            {{ $countrySwitch->getLabel($country) }}
                        </span>

                    @endif
                </button>
            @endif
        @endforeach
    </x-filament::dropdown.list>
</x-filament::dropdown>
