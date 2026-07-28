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
        $this->is_published = false;
        $this->save();
    }

    /**
     * Retourne une instance (clonée, jamais l'original) dont les attributs
     * sont ceux figés lors de la dernière publication, pour un affichage
     * front fidèle à l'état publié. Cette instance clonée ne doit jamais
     * être sauvegardée : ce n'est qu'une projection en lecture pour l'affichage,
     * pas le contenu brouillon réel du modèle.
     */
    public function withPublishedContent(): static
    {
        if (empty($this->published_snapshot)) {
            return $this;
        }

        $clone = clone $this;
        $clone->setRawAttributes(array_merge($clone->getAttributes(), $clone->published_snapshot), true);

        return $clone;
    }

    protected function publishableAttributes(): array
    {
        return array_values(array_diff($this->getFillable(), ['is_published']));
    }
}
