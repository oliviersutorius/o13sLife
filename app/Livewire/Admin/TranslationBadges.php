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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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

    /** Translation status is 'validated', 'auto', or 'missing' per locale. */
    private const STATUS_VALIDATED = 'validated';

    private const STATUS_AUTO = 'auto';

    private const STATUS_MISSING = 'missing';

    public string $modelClass;

    public int $modelId;

    public array $fields;

    /** @var array<string, string> locale → 'validated'|'auto'|'missing' */
    public array $translationStatus = [];

    public string $successMessage = '';

    public bool $showEditor = false;

    /** @var array<string, array<string, string>> locale → field → value */
    public array $translations = [];

    protected ?Model $cachedModel = null;

    public function mount(string $modelClass, int $modelId, array $fields): void
    {
        abort_unless(in_array($modelClass, self::ALLOWED_MODELS, true), 403);

        $this->modelClass = $modelClass;
        $this->modelId = $modelId;
        $this->fields = $fields;
        $this->translationStatus = $this->computeTranslationStatus();
        $this->initTranslations();
    }

    protected function loadModel(): Model
    {
        if ($this->cachedModel === null) {
            $this->cachedModel = $this->modelClass::findOrFail($this->modelId);
        }

        return $this->cachedModel;
    }

    /** @return array<string, string> */
    protected function computeTranslationStatus(): array
    {
        $model = $this->loadModel();
        $validatedKeys = $model->translations_validated ?? [];
        $status = [];

        foreach (SetLocale::SUPPORTED_LOCALES as $locale) {
            $allFilled = true;
            $allValidated = true;

            foreach ($this->fields as $field) {
                $value = $model->getTranslation($field, $locale, false);

                if (empty($value)) {
                    $allFilled = false;
                    $allValidated = false;
                    break;
                }

                if (! in_array("{$field}.{$locale}", $validatedKeys, true)) {
                    $allValidated = false;
                }
            }

            if (! $allFilled) {
                $status[$locale] = self::STATUS_MISSING;
            } elseif ($allValidated) {
                $status[$locale] = self::STATUS_VALIDATED;
            } else {
                $status[$locale] = self::STATUS_AUTO;
            }
        }

        return $status;
    }

    protected function initTranslations(): void
    {
        $model = $this->loadModel();
        $this->translations = [];

        foreach (SetLocale::SUPPORTED_LOCALES as $locale) {
            if ($locale === 'fr') {
                continue;
            }

            foreach ($this->fields as $field) {
                $this->translations[$locale][$field] = $model->getTranslation($field, $locale, false);
            }
        }
    }

    public function toggleEditor(): void
    {
        $this->showEditor = ! $this->showEditor;

        if ($this->showEditor) {
            $this->cachedModel = null;
            $this->initTranslations();
        }

        $this->successMessage = '';
    }

    public function sauvegarderTraductions(): void
    {
        abort_unless(in_array($this->modelClass, self::ALLOWED_MODELS, true), 403);

        $model = $this->loadModel();
        $validatedKeys = $model->translations_validated ?? [];

        foreach ($this->translations as $locale => $fields) {
            foreach ($fields as $field => $value) {
                $model->setTranslation($field, $locale, (string) $value);

                $key = "{$field}.{$locale}";

                if (! empty($value)) {
                    if (! in_array($key, $validatedKeys, true)) {
                        $validatedKeys[] = $key;
                    }
                } else {
                    $validatedKeys = array_values(array_filter(
                        $validatedKeys,
                        fn ($k) => $k !== $key,
                    ));
                }
            }
        }

        $model->translations_validated = array_values($validatedKeys);
        $model->save();

        $this->translationStatus = $this->computeTranslationStatus();
        $this->showEditor = false;
        $this->successMessage = __('common.traductions_sauvegardees');
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

    /** Human-readable label for a field name. */
    public function labelFor(string $field): string
    {
        return Str::headline($field);
    }

    /** Reference (FR) value for a field. */
    public function frValue(string $field): string
    {
        return $this->loadModel()->getTranslation($field, 'fr', false);
    }

    public function render(): View
    {
        return view('livewire.admin.translation-badges');
    }
}
