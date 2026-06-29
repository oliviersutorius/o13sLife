<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Http\Middleware\SetLocale;
use App\Jobs\TranslateContentJob;
use App\Models\CentreInteret;
use App\Models\Competence;
use App\Models\Experience;
use App\Models\Formation;
use App\Models\Langue;
use App\Models\Profil;
use Illuminate\View\View;
use Livewire\Component;

class TranslationBadges extends Component
{
    private const ALLOWED_MODELS = [
        Profil::class,
        Experience::class,
        Formation::class,
        Competence::class,
        Langue::class,
        CentreInteret::class,
    ];

    public const LOCALE_FLAGS = [
        'fr' => '🇫🇷',
        'en' => '🇬🇧',
        'it' => '🇮🇹',
        'es' => '🇪🇸',
    ];

    public string $modelClass;

    public int $modelId;

    public array $fields;

    /** @var array<string, bool> */
    public array $translationStatus = [];

    public string $successMessage = '';

    public function mount(string $modelClass, int $modelId, array $fields): void
    {
        abort_unless(in_array($modelClass, self::ALLOWED_MODELS, true), 403);

        $this->modelClass = $modelClass;
        $this->modelId = $modelId;
        $this->fields = $fields;
        $this->translationStatus = $this->computeTranslationStatus();
    }

    /** @return array<string, bool> */
    public function computeTranslationStatus(): array
    {
        $model = $this->modelClass::findOrFail($this->modelId);
        $status = [];

        foreach (SetLocale::SUPPORTED_LOCALES as $locale) {
            $hasAll = true;

            foreach ($this->fields as $field) {
                if (empty($model->getTranslation($field, $locale, false))) {
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
        abort_unless(in_array($this->modelClass, self::ALLOWED_MODELS, true), 403);

        TranslateContentJob::dispatch(
            $this->modelClass,
            $this->modelId,
            $this->fields,
        );

        $this->successMessage = __('common.traduction_en_cours');
    }

    public function render(): View
    {
        return view('livewire.admin.translation-badges');
    }
}
