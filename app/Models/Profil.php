<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Profil extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['titre'];

    protected $fillable = [
        'photo',
        'titre',
        'email',
        'telephone',
        'localisation',
        'lien_linkedin',
        'lien_github',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'translations_validated' => 'array',
        ];
    }
}
