<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Http\Middleware\SetLocale;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Sélecteur de langue de la page publique (drapeaux SVG). La locale effective
 * est appliquée par le middleware `SetLocale` via le cookie `locale`.
 */
class LocaleSwitcher extends Component
{
    public string $currentLocale;

    public function mount(): void
    {
        $this->currentLocale = app()->getLocale();
    }

    /** Switch the active locale; silently ignores unsupported values. */
    public function switchLocale(string $locale): void
    {
        if (! in_array($locale, SetLocale::SUPPORTED_LOCALES, true)) {
            return;
        }

        $this->currentLocale = $locale;

        $this->dispatch('locale-changed', locale: $locale);
    }

    public function render(): View
    {
        return view('livewire.locale-switcher', [
            'locales' => SetLocale::LOCALES,
        ]);
    }
}
