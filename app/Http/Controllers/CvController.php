<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CentreInteret;
use App\Models\Competence;
use App\Models\Experience;
use App\Models\Formation;
use App\Models\Langue;
use App\Models\Profil;
use Illuminate\Support\Str;

class CvController extends Controller
{
    public function index()
    {
        $profil = Profil::published()->first()?->withPublishedContent();

        $experiences = Experience::published()->get()
            ->map->withPublishedContent()
            ->sortByDesc('date_debut')
            ->values();

        $formations = Formation::published()->get()
            ->map->withPublishedContent()
            ->sortByDesc('annee')
            ->values();

        $competences = Competence::published()->get()
            ->map->withPublishedContent()
            ->sortBy(fn (Competence $competence) => mb_strtolower(Str::ascii($competence->categorie)).'|'.mb_strtolower(Str::ascii($competence->nom)))
            ->values()
            ->groupBy('categorie');

        $langues = Langue::published()->get()->map->withPublishedContent();
        $centresInteret = CentreInteret::published()->get()->map->withPublishedContent();

        return view('cv', compact(
            'profil',
            'experiences',
            'formations',
            'competences',
            'langues',
            'centresInteret',
        ));
    }
}
