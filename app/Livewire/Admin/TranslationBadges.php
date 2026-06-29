<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Http\Middleware\SetLocale;
use App\Jobs\TranslateContentJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;
use Livewire\Component;

class TranslationBadges extends Component
{
    public string $modelClass;

    public int $modelId;

    public array $fields;

    public bool $translating = false;

    public string $successMessage = '';

    public const LOCALE_FLAGS = [
        'fr' => '🇫🇷',
        'en' => '🇬🇧',
        'it' => '🇮🇹',
        'es' => '🇪🇸',
    ];

    public function mount(string $modelClass, int $modelId, array $fields): void
    {
        $this->modelClass = $modelClass;
        $this->modelId = $modelId;
        $this->fields = $fields;
    }

    public function getTranslationStatus(): array
    {
        /** @var Model $model */
        $model = $this->modelClass::findOrFail($this->modelId);
        $status = [];

        foreach (SetLocale::SUPPORTED_LOCALES as $locale) {
            $hasAll = true;

            foreach ($this->fields as $field) {
                $value = $model->getTranslation($field, $locale, false);

                if (empty($value)) {
                    $hasAll = false;
                    break;
                }
            }

            $status[$locale] = $hasAll;
        }

        return $status;
    }

    public function traduire(): void
    {
        $this->translating = true;

        TranslateContentJob::dispatch(
            $this->modelClass,
            $this->modelId,
            $this->fields,
        );

        $this->successMessage = 'Traduction en cours…';
    }

    public function render(): View
    {
        return view('livewire.admin.translation-badges', [
            'translationStatus' => $this->getTranslationStatus(),
        ]);
    }
}
