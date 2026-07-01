<?php

declare(strict_types=1);

use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

it('applique la locale présente dans le cookie', function () {
    $middleware = new SetLocale;
    $request = Request::create('/', 'GET');
    $request->cookies->set('locale', 'en');

    $middleware->handle($request, fn () => new Response);

    expect(app()->getLocale())->toBe('en');
});

it('fallback à fr pour une locale non supportée dans le cookie', function () {
    $middleware = new SetLocale;
    $request = Request::create('/', 'GET');
    $request->cookies->set('locale', 'zh');

    $middleware->handle($request, fn () => new Response);

    expect(app()->getLocale())->toBe('fr');
});

it('utilise la locale fr par défaut quand aucun cookie n\'est présent', function () {
    $middleware = new SetLocale;
    $request = Request::create('/', 'GET');

    $middleware->handle($request, fn () => new Response);

    expect(app()->getLocale())->toBe('fr');
});

it('accepte toutes les locales supportées', function (string $locale) {
    $middleware = new SetLocale;
    $request = Request::create('/', 'GET');
    $request->cookies->set('locale', $locale);

    $middleware->handle($request, fn () => new Response);

    expect(app()->getLocale())->toBe($locale);
})->with(['fr', 'en', 'it', 'es', 'de']);
