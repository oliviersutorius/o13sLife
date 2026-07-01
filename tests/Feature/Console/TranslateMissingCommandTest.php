<?php

declare(strict_types=1);

use App\Jobs\TranslateContentJob;
use App\Models\Experience;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

it('dispatche un job pour toutes les locales non-FR quand des traductions manquent', function () {
    Bus::fake();

    $experience = Experience::factory()->create();
    // Forcer des traductions FR uniquement pour garantir l'état initial.
    DB::table('experiences')->where('id', $experience->id)->update([
        'titre_poste' => json_encode(['fr' => 'Développeur']),
        'description' => json_encode(['fr' => 'Description test']),
    ]);

    $this->artisan('cv:translate-missing')
        ->assertSuccessful();

    Bus::assertDispatched(TranslateContentJob::class, 1);
    Bus::assertDispatched(TranslateContentJob::class, function (TranslateContentJob $job): bool {
        return in_array('en', $job->targetLocales, true)
            && in_array('it', $job->targetLocales, true)
            && in_array('es', $job->targetLocales, true)
            && in_array('de', $job->targetLocales, true);
    });
});

it('dispatche uniquement pour la locale spécifiée avec un argument valide', function () {
    Bus::fake();

    $experience = Experience::factory()->create();
    DB::table('experiences')->where('id', $experience->id)->update([
        'titre_poste' => json_encode(['fr' => 'Développeur']),
        'description' => json_encode(['fr' => 'Description test']),
    ]);

    $this->artisan('cv:translate-missing', ['locale' => 'de'])
        ->assertSuccessful();

    Bus::assertDispatched(TranslateContentJob::class, 1);
    Bus::assertDispatched(TranslateContentJob::class, function (TranslateContentJob $job): bool {
        return $job->targetLocales === ['de'];
    });
});

it('retourne FAILURE et affiche une erreur pour une locale invalide', function () {
    $this->artisan('cv:translate-missing', ['locale' => 'zz'])
        ->assertFailed();
});

it('dispatche 0 jobs quand toutes les traductions sont déjà présentes', function () {
    Bus::fake();

    $experience = Experience::factory()->create();
    DB::table('experiences')->where('id', $experience->id)->update([
        'titre_poste' => json_encode(['fr' => 'Dev', 'en' => 'Dev', 'it' => 'Dev', 'es' => 'Dev', 'de' => 'Dev']),
        'description' => json_encode(['fr' => 'Desc', 'en' => 'Desc', 'it' => 'Desc', 'es' => 'Desc', 'de' => 'Desc']),
    ]);

    $this->artisan('cv:translate-missing')
        ->assertSuccessful();

    Bus::assertNothingDispatched();
});
