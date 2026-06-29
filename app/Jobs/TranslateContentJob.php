<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\TranslationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;

class TranslateContentJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $modelClass,
        public readonly int $modelId,
        public readonly array $fields,
        public readonly array $targetLocales = ['en', 'it', 'es'],
    ) {}

    public function handle(TranslationService $service): void
    {
        /** @var Model $model */
        $model = $this->modelClass::findOrFail($this->modelId);

        foreach ($this->fields as $field) {
            $sourcText = $model->getTranslation($field, 'fr');

            if (empty($sourcText)) {
                continue;
            }

            foreach ($this->targetLocales as $locale) {
                $translated = $service->translate($sourcText, 'fr', $locale);
                $model->setTranslation($field, $locale, $translated);
            }
        }

        $model->save();
    }
}
