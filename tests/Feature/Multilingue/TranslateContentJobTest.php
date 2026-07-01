<?php

declare(strict_types=1);

use App\Jobs\TranslateContentJob;
use App\Models\Experience;
use App\Services\TranslationService;
use Illuminate\Support\Facades\DB;

it('traduit les champs du modèle dans les locales cibles', function () {
    $experience = Experience::factory()->create();
    DB::table('experiences')->where('id', $experience->id)->update([
        'titre_poste' => json_encode(['fr' => 'Développeur']),
    ]);

    $service = Mockery::mock(TranslationService::class);
    $service->shouldReceive('translate')
        ->times(4)
        ->andReturnValues(['Developer', 'Sviluppatore', 'Desarrollador', 'Entwickler']);

    $job = new TranslateContentJob(Experience::class, $experience->id, ['titre_poste']);
    $job->handle($service);

    $experience->refresh();
    expect($experience->getTranslation('titre_poste', 'en', false))->toBe('Developer')
        ->and($experience->getTranslation('titre_poste', 'it', false))->toBe('Sviluppatore')
        ->and($experience->getTranslation('titre_poste', 'es', false))->toBe('Desarrollador')
        ->and($experience->getTranslation('titre_poste', 'de', false))->toBe('Entwickler');
});

it('ignore les champs vides et ne traduit pas', function () {
    $experience = Experience::factory()->create();
    DB::table('experiences')->where('id', $experience->id)->update([
        'titre_poste' => json_encode(['fr' => '']),
    ]);

    $service = Mockery::mock(TranslationService::class);
    $service->shouldReceive('translate')->never();

    $job = new TranslateContentJob(Experience::class, $experience->id, ['titre_poste']);
    $job->handle($service);
});

it('continue les autres traductions si une échoue', function () {
    $experience = Experience::factory()->create();
    DB::table('experiences')->where('id', $experience->id)->update([
        'titre_poste' => json_encode(['fr' => 'Développeur']),
    ]);

    $service = Mockery::mock(TranslationService::class);
    $service->shouldReceive('translate')->with('Développeur', 'fr', 'en')->andThrow(new RuntimeException('API error'));
    $service->shouldReceive('translate')->with('Développeur', 'fr', 'it')->andReturn('Sviluppatore');
    $service->shouldReceive('translate')->with('Développeur', 'fr', 'es')->andReturn('Desarrollador');
    $service->shouldReceive('translate')->with('Développeur', 'fr', 'de')->andReturn('Entwickler');

    $job = new TranslateContentJob(Experience::class, $experience->id, ['titre_poste']);
    $job->handle($service);

    $experience->refresh();
    expect($experience->getTranslation('titre_poste', 'en', false))->toBe('')
        ->and($experience->getTranslation('titre_poste', 'it', false))->toBe('Sviluppatore')
        ->and($experience->getTranslation('titre_poste', 'es', false))->toBe('Desarrollador')
        ->and($experience->getTranslation('titre_poste', 'de', false))->toBe('Entwickler');
});

it('accepte des locales cibles personnalisées', function () {
    $experience = Experience::factory()->create();
    DB::table('experiences')->where('id', $experience->id)->update([
        'titre_poste' => json_encode(['fr' => 'Développeur']),
    ]);

    $service = Mockery::mock(TranslationService::class);
    $service->shouldReceive('translate')->once()->with('Développeur', 'fr', 'en')->andReturn('Developer');

    $job = new TranslateContentJob(Experience::class, $experience->id, ['titre_poste'], ['en']);
    $job->handle($service);

    $experience->refresh();
    expect($experience->getTranslation('titre_poste', 'en', false))->toBe('Developer');
});
