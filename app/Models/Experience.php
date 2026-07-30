<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublishedSnapshot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Poste occupé dans le parcours professionnel, affiché par date de début décroissante.
 */
class Experience extends Model
{
    use HasFactory, HasPublishedSnapshot, HasTranslations;

    public array $translatable = ['titre_poste', 'description'];

    protected $fillable = [
        'titre_poste',
        'entreprise',
        'date_debut',
        'date_fin',
        'description',
        'technologies',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin' => 'date',
            'technologies' => 'array',
            'is_published' => 'boolean',
            'published_snapshot' => 'array',
            'translations_validated' => 'array',
        ];
    }
}
