<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublishedSnapshot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Competence extends Model
{
    use HasFactory, HasPublishedSnapshot, HasTranslations;

    public array $translatable = ['categorie', 'nom'];

    protected $fillable = [
        'categorie',
        'nom',
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
