<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublishedSnapshot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CentreInteret extends Model
{
    use HasFactory, HasPublishedSnapshot, HasTranslations;

    public array $translatable = ['libelle'];

    protected $table = 'centres_interet';

    protected $fillable = [
        'libelle',
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
