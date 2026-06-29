<?php

declare(strict_types=1);

use App\Jobs\TranslateContentJob;
use App\Livewire\Admin\TranslationBadges;
use App\Models\Experience;
use App\Models\Profil;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Symfony\Component\HttpKernel\Exception\HttpException;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('affiche les badges de traduction pour un modèle autorisé', function () {
    $profil = Profil::factory()->create(['email' => 'test@example.com']);
    DB::table('profils')->where('id', $profil->id)->update([
        'titre' => json_encode(['fr' => 'Dev', 'en' => 'Dev', 'it' => 'Dev', 'es' => 'Dev']),
    ]);

    Livewire::test(TranslationBadges::class, [
        'modelClass' => Profil::class,
        'modelId' => $profil->id,
        'fields' => ['titre'],
    ])
        ->assertSee('🇫🇷')
        ->assertSee('🇬🇧')
        ->assertSee('🇮🇹')
        ->assertSee('🇪🇸');
});

it('refuse un modelClass non autorisé avec 403', function () {
    $component = new TranslationBadges;
    $component->mount(User::class, 1, ['name']);
})->throws(HttpException::class);

it('initialise translationStatus depuis mount', function () {
    $profil = Profil::factory()->create(['email' => 'test@example.com']);
    DB::table('profils')->where('id', $profil->id)->update([
        'titre' => json_encode(['fr' => 'Développeur']),
    ]);
    $profil->refresh();

    $component = Livewire::test(TranslationBadges::class, [
        'modelClass' => Profil::class,
        'modelId' => $profil->id,
        'fields' => ['titre'],
    ]);

    $status = $component->get('translationStatus');
    expect($status)->toBeArray()
        ->and($status['fr'])->toBeTrue()
        ->and($status['en'])->toBeFalse();
});

it('dispatche le job de traduction et affiche le message de succès', function () {
    Queue::fake();

    $experience = Experience::factory()->create();

    Livewire::test(TranslationBadges::class, [
        'modelClass' => Experience::class,
        'modelId' => $experience->id,
        'fields' => ['titre_poste'],
    ])
        ->call('traduire')
        ->assertSet('successMessage', __('common.traduction_en_cours'));

    Queue::assertPushed(TranslateContentJob::class);
});

it('empêche l\'injection via modelClass lors du traduire', function () {
    $profil = Profil::factory()->create(['email' => 'test@example.com']);

    $component = new TranslationBadges;
    $component->mount(Profil::class, $profil->id, ['titre']);
    $component->modelClass = User::class;
    $component->traduire();
})->throws(HttpException::class);
