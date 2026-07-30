<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublishedSnapshot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Diplôme ou certification obtenu.
 */
class Formation extends Model
{
    use HasFactory, HasPublishedSnapshot, HasTranslations;

    public array $translatable = ['diplome'];

    protected $fillable = [
        'ecole',
        'annee',
        'diplome',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'annee' => 'integer',
            'is_published' => 'boolean',
            'published_snapshot' => 'array',
            'translations_validated' => 'array',
        ];
    }
}
