<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const LOCALES = [
        'fr' => ['label' => 'Français', 'flag' => 'fr', 'name' => 'French'],
        'en' => ['label' => 'English',  'flag' => 'gb', 'name' => 'English'],
        'it' => ['label' => 'Italiano', 'flag' => 'it', 'name' => 'Italian'],
        'es' => ['label' => 'Español',  'flag' => 'es', 'name' => 'Spanish'],
        'de' => ['label' => 'Deutsch',  'flag' => 'de', 'name' => 'German'],
    ];

    public const SUPPORTED_LOCALES = ['fr', 'en', 'it', 'es', 'de'];

    public const DEFAULT_LOCALE = 'fr';

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie('locale', self::DEFAULT_LOCALE);

        if (! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = self::DEFAULT_LOCALE;
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
