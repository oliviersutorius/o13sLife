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

it('initialise translationStatus depuis mount avec les bons statuts', function () {
    $profil = Profil::factory()->create(['email' => 'test@example.com']);
    DB::table('profils')->where('id', $profil->id)->update([
        'titre' => json_encode(['fr' => 'Développeur']),
    ]);

    $component = Livewire::test(TranslationBadges::class, [
        'modelClass' => Profil::class,
        'modelId' => $profil->id,
        'fields' => ['titre'],
    ]);

    $status = $component->get('translationStatus');
    expect($status)->toBeArray()
        ->and($status['fr'])->toBe('auto')    // filled but not manually validated
        ->and($status['en'])->toBe('missing') // no translation yet
        ->and($status['it'])->toBe('missing')
        ->and($status['es'])->toBe('missing');
});

it('retourne le statut validated quand les clés sont dans translations_validated', function () {
    $profil = Profil::factory()->create(['email' => 'test@example.com']);
    DB::table('profils')->where('id', $profil->id)->update([
        'titre' => json_encode(['fr' => 'Développeur', 'en' => 'Developer', 'it' => 'Sviluppatore', 'es' => 'Desarrollador']),
        'translations_validated' => json_encode(['titre.fr', 'titre.en', 'titre.it', 'titre.es']),
    ]);

    $component = Livewire::test(TranslationBadges::class, [
        'modelClass' => Profil::class,
        'modelId' => $profil->id,
        'fields' => ['titre'],
    ]);

    $status = $component->get('translationStatus');
    expect($status['fr'])->toBe('validated')
        ->and($status['en'])->toBe('validated')
        ->and($status['it'])->toBe('validated')
        ->and($status['es'])->toBe('validated');
});

it('retourne le statut auto quand les traductions existent mais ne sont pas validées', function () {
    $profil = Profil::factory()->create(['email' => 'test@example.com']);
    DB::table('profils')->where('id', $profil->id)->update([
        'titre' => json_encode(['fr' => 'Développeur', 'en' => 'Developer', 'it' => 'Sviluppatore', 'es' => 'Desarrollador']),
        'translations_validated' => null,
    ]);

    $component = Livewire::test(TranslationBadges::class, [
        'modelClass' => Profil::class,
        'modelId' => $profil->id,
        'fields' => ['titre'],
    ]);

    $status = $component->get('translationStatus');
    expect($status['fr'])->toBe('auto')
        ->and($status['en'])->toBe('auto')
        ->and($status['it'])->toBe('auto')
        ->and($status['es'])->toBe('auto');
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

it('toggleEditor bascule la visibilité du panneau d\'édition', function () {
    $profil = Profil::factory()->create(['email' => 'test@example.com']);
    DB::table('profils')->where('id', $profil->id)->update([
        'titre' => json_encode(['fr' => 'Développeur']),
    ]);

    $component = Livewire::test(TranslationBadges::class, [
        'modelClass' => Profil::class,
        'modelId' => $profil->id,
        'fields' => ['titre'],
    ]);

    $component->assertSet('showEditor', false);
    $component->call('toggleEditor')->assertSet('showEditor', true);
    $component->call('toggleEditor')->assertSet('showEditor', false);
});

it('sauvegarderTraductions persiste les traductions et marque comme validated', function () {
    $profil = Profil::factory()->create(['email' => 'test@example.com']);
    DB::table('profils')->where('id', $profil->id)->update([
        'titre' => json_encode(['fr' => 'Développeur']),
        'translations_validated' => null,
    ]);

    $component = Livewire::test(TranslationBadges::class, [
        'modelClass' => Profil::class,
        'modelId' => $profil->id,
        'fields' => ['titre'],
    ]);

    $component->call('toggleEditor');
    $component->set('translations.en.titre', 'Developer');
    $component->call('sauvegarderTraductions');

    $component->assertSet('showEditor', false);
    $component->assertSet('successMessage', __('common.traductions_sauvegardees'));

    $profil->refresh();
    expect($profil->getTranslation('titre', 'en', false))->toBe('Developer');
    expect($profil->translations_validated)->toContain('titre.en');
});

it('sauvegarderTraductions retire la clé validated si la valeur est vidée', function () {
    $profil = Profil::factory()->create(['email' => 'test@example.com']);
    DB::table('profils')->where('id', $profil->id)->update([
        'titre' => json_encode(['fr' => 'Développeur', 'en' => 'Developer']),
        'translations_validated' => json_encode(['titre.en']),
    ]);

    $component = Livewire::test(TranslationBadges::class, [
        'modelClass' => Profil::class,
        'modelId' => $profil->id,
        'fields' => ['titre'],
    ]);

    $component->call('toggleEditor');
    $component->set('translations.en.titre', '');
    $component->call('sauvegarderTraductions');

    $profil->refresh();
    expect($profil->translations_validated)->not->toContain('titre.en');
});

it('sauvegarderTraductions refuse un modelClass non autorisé avec 403', function () {
    $profil = Profil::factory()->create(['email' => 'test@example.com']);

    $component = new TranslationBadges;
    $component->mount(Profil::class, $profil->id, ['titre']);
    $component->modelClass = User::class;
    $component->sauvegarderTraductions();
})->throws(HttpException::class);

it('labelFor retourne un libellé lisible pour un nom de champ', function () {
    $experience = Experience::factory()->create();

    $component = new TranslationBadges;
    $component->mount(Experience::class, $experience->id, ['titre_poste']);

    expect($component->labelFor('titre_poste'))->toBe('Titre Poste');
});

it('frValue retourne la valeur FR du champ', function () {
    $profil = Profil::factory()->create(['email' => 'test@example.com']);
    DB::table('profils')->where('id', $profil->id)->update([
        'titre' => json_encode(['fr' => 'Développeur', 'en' => 'Developer']),
    ]);

    $component = new TranslationBadges;
    $component->mount(Profil::class, $profil->id, ['titre']);

    expect($component->frValue('titre'))->toBe('Développeur');
});

it('la locale de apparaît avec statut missing quand aucune traduction DE n\'existe', function () {
    $profil = Profil::factory()->create(['email' => 'test@example.com']);
    DB::table('profils')->where('id', $profil->id)->update([
        'titre' => json_encode(['fr' => 'Développeur', 'en' => 'Developer', 'it' => 'Sviluppatore', 'es' => 'Desarrollador']),
    ]);

    $component = Livewire::test(TranslationBadges::class, [
        'modelClass' => Profil::class,
        'modelId' => $profil->id,
        'fields' => ['titre'],
    ]);

    $status = $component->get('translationStatus');
    expect($status['de'])->toBe('missing');
});
