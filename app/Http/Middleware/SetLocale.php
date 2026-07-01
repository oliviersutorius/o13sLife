<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const LOCALES = [
        'fr' => ['label' => 'Français', 'flag' => '🇫🇷', 'name' => 'French'],
        'en' => ['label' => 'English',  'flag' => '🇬🇧', 'name' => 'English'],
        'it' => ['label' => 'Italiano', 'flag' => '🇮🇹', 'name' => 'Italian'],
        'es' => ['label' => 'Español',  'flag' => '🇪🇸', 'name' => 'Spanish'],
        'de' => ['label' => 'Deutsch',  'flag' => '🇩🇪', 'name' => 'German'],
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
