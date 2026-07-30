<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublishedSnapshot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Langue maîtrisée par l'administrateur, avec niveau libre (≠ locale d'affichage de l'interface).
 */
class Langue extends Model
{
    use HasFactory, HasPublishedSnapshot, HasTranslations;

    public array $translatable = ['niveau'];

    protected $fillable = [
        'langue',
        'niveau',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_snapshot' => 'array',
            'translations_validated' => 'array',
        ];
    }
}
