<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Formation extends Model
{
    use HasFactory, HasTranslations;

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
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}
