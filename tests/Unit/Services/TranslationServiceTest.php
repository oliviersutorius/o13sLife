<?php

declare(strict_types=1);

use Anthropic\Exceptions\ErrorException;
use App\Services\TranslationService;
use Tests\TestCase;

// Utilise Tests\TestCase pour que le conteneur Laravel soit disponible
// (nécessaire pour lier les mocks via app()->instance()).
uses(TestCase::class);

it('retourne le texte traduit trimmé', function () {
    $mockResponse = Mockery::mock();
    $mockResponse->content = [(object) ['text' => '  Developer  ']];

    $mockMessages = Mockery::mock();
    $mockMessages->shouldReceive('create')->once()->andReturn($mockResponse);

    $mockClient = Mockery::mock();
    $mockClient->shouldReceive('messages')->once()->andReturn($mockMessages);

    // Lie le mock au nom de façade 'anthropic' dans le conteneur.
    app()->instance('anthropic', $mockClient);

    $service = new TranslationService;
    $result = $service->translate('Développeur', 'fr', 'en');

    expect($result)->toBe('Developer');
});

it('propage l\'exception ErrorException quand l\'API échoue', function () {
    $mockMessages = Mockery::mock();
    $mockMessages->shouldReceive('create')
        ->once()
        ->andThrow(new ErrorException('API failure'));

    $mockClient = Mockery::mock();
    $mockClient->shouldReceive('messages')->once()->andReturn($mockMessages);

    app()->instance('anthropic', $mockClient);

    $service = new TranslationService;
    $service->translate('Développeur', 'fr', 'en');
})->throws(ErrorException::class);
