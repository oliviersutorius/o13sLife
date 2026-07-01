<?php

declare(strict_types=1);

use App\Livewire\LocaleSwitcher;
use Livewire\Livewire;

it('affiche les 5 drapeaux de langue', function () {
    Livewire::test(LocaleSwitcher::class)
        ->assertSee('🇫🇷')
        ->assertSee('🇬🇧')
        ->assertSee('🇮🇹')
        ->assertSee('🇪🇸')
        ->assertSee('🇩🇪');
});

it('démarre avec la locale de l\'application', function () {
    app()->setLocale('fr');

    Livewire::test(LocaleSwitcher::class)
        ->assertSet('currentLocale', 'fr');
});

it('change la locale courante lors du switchLocale', function () {
    Livewire::test(LocaleSwitcher::class)
        ->call('switchLocale', 'en')
        ->assertSet('currentLocale', 'en');
});

it('ignore une locale non supportée', function () {
    app()->setLocale('fr');

    Livewire::test(LocaleSwitcher::class)
        ->call('switchLocale', 'zh')
        ->assertSet('currentLocale', 'fr');
});

it('accepte la nouvelle locale DE', function () {
    Livewire::test(LocaleSwitcher::class)
        ->call('switchLocale', 'de')
        ->assertSet('currentLocale', 'de')
        ->assertDispatched('locale-changed', locale: 'de');
});

it('dispatche l\'événement locale-changed lors du changement', function () {
    Livewire::test(LocaleSwitcher::class)
        ->call('switchLocale', 'it')
        ->assertDispatched('locale-changed', locale: 'it');
});
