<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

/**
 * Sépare le contenu brouillon (colonnes courantes, modifiables librement)
 * du contenu publié (snapshot figé au moment de la publication) : la page
 * publique du CV ne doit jamais refléter une modification tant qu'elle n'a
 * pas été explicitement (re)publiée.
 */
trait HasPublishedSnapshot
{
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function publish(): void
    {
        $this->published_snapshot = Arr::only($this->getAttributes(), $this->publishableAttributes());
        $this->is_published = true;
        $this->save();
    }

    public function unpublish(): void
    {
        $this->update(['is_published' => false]);
    }

    /**
     * Remplace les attributs courants par ceux figés lors de la dernière
     * publication, pour un affichage front fidèle à l'état publié.
     */
    public function withPublishedContent(): static
    {
        if (! empty($this->published_snapshot)) {
            $this->setRawAttributes(array_merge($this->getAttributes(), $this->published_snapshot), true);
        }

        return $this;
    }

    protected function publishableAttributes(): array
    {
        return array_values(array_diff($this->getFillable(), ['is_published']));
    }
}
