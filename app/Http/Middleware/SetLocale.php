<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const SUPPORTED_LOCALES = ['fr', 'en', 'it', 'es'];

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
