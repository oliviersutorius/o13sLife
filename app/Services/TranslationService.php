<?php

declare(strict_types=1);

namespace App\Services;

use Anthropic\Exceptions\ErrorException;
use Anthropic\Laravel\Facades\Anthropic;
use App\Http\Middleware\SetLocale;

class TranslationService
{
    /**
     * Translate a text from one locale to another using Claude Haiku.
     *
     * @throws ErrorException on API failure
     */
    public function translate(string $text, string $sourceLocale, string $targetLocale): string
    {
        $sourceName = SetLocale::LOCALES[$sourceLocale]['name'] ?? $sourceLocale;
        $targetName = SetLocale::LOCALES[$targetLocale]['name'] ?? $targetLocale;

        $response = Anthropic::messages()->create([
            'model' => 'claude-haiku-4-5-20251001',
            'max_tokens' => 1024,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => "Translate the following CV content from {$sourceName} to {$targetName}. Return only the translated text, no explanation, no quotes.\n\n{$text}",
                ],
            ],
        ]);

        return trim($response->content[0]->text);
    }
}
