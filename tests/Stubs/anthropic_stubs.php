<?php

declare(strict_types=1);

/**
 * Stubs minimaux pour le package anthropic-php/laravel, non installé en dev.
 * Chargés uniquement dans le contexte des tests, via tests/Pest.php.
 */

namespace Anthropic\Exceptions {
    if (! class_exists(ErrorException::class)) {
        class ErrorException extends \Exception {}
    }
}

namespace Anthropic\Laravel\Facades {
    use Illuminate\Support\Facades\Facade;

    if (! class_exists(Anthropic::class)) {
        class Anthropic extends Facade
        {
            protected static function getFacadeAccessor(): string
            {
                return 'anthropic';
            }
        }
    }
}
