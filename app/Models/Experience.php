<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Experience extends Model
{
    use HasFactory, HasTranslations;

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
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('date_debut', 'desc');
    }
}
