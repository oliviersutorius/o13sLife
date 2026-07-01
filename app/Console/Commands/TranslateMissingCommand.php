<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Middleware\SetLocale;
use App\Jobs\TranslateContentJob;
use App\Models\CentreInteret;
use App\Models\Competence;
use App\Models\Experience;
use App\Models\Formation;
use App\Models\Langue;
use App\Models\Profil;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('cv:translate-missing {locale? : Locale cible (ex: de). Toutes les locales non-FR par défaut.}')]
#[Description('Dispatche les jobs de traduction pour les entrées dont une locale est manquante.')]
class TranslateMissingCommand extends Command
{
    private const MODELS = [
        Profil::class => ['titre'],
        Experience::class => ['titre_poste', 'description'],
        Formation::class => ['diplome'],
        Competence::class => ['categorie', 'nom'],
        Langue::class => ['niveau'],
        CentreInteret::class => ['libelle'],
    ];

    public function handle(): int
    {
        $targetLocale = $this->argument('locale');

        if ($targetLocale !== null && ! array_key_exists($targetLocale, SetLocale::LOCALES)) {
            $this->error("Locale « {$targetLocale} » non supportée. Locales disponibles : ".implode(', ', array_keys(SetLocale::LOCALES)));

            return self::FAILURE;
        }

        $targetLocales = $targetLocale !== null
            ? [$targetLocale]
            : array_filter(array_keys(SetLocale::LOCALES), fn ($l) => $l !== 'fr');

        $this->info('Locales cibles : '.implode(', ', $targetLocales));
        $this->newLine();

        $total = 0;

        foreach (self::MODELS as $modelClass => $fields) {
            $shortName = class_basename($modelClass);
            $records = $modelClass::all();
            $dispatched = 0;

            foreach ($records as $record) {
                $missingLocales = array_values(array_filter(
                    $targetLocales,
                    fn ($locale) => $this->hasMissingTranslation($record, $fields, $locale),
                ));

                if (empty($missingLocales)) {
                    continue;
                }

                TranslateContentJob::dispatch($modelClass, $record->id, $fields, $missingLocales);
                $dispatched++;
            }

            $this->line("  {$shortName} : {$dispatched}/{$records->count()} entrée(s) envoyées en traduction");
            $total += $dispatched;
        }

        $this->newLine();
        $this->info("{$total} job(s) de traduction dispatchés.");

        return self::SUCCESS;
    }

    private function hasMissingTranslation(mixed $record, array $fields, string $locale): bool
    {
        foreach ($fields as $field) {
            $value = $record->getTranslation($field, $locale, false);
            if (empty($value)) {
                return true;
            }
        }

        return false;
    }
}
