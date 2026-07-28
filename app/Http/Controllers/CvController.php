<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CentreInteret;
use App\Models\Competence;
use App\Models\Experience;
use App\Models\Formation;
use App\Models\Langue;
use App\Models\Profil;

class CvController extends Controller
{
    public function index()
    {
        $profil = Profil::published()->first()?->withPublishedContent();

        $experiences = Experience::published()->get()
            ->each->withPublishedContent()
            ->sortByDesc('date_debut')
            ->values();

        $formations = Formation::published()->get()
            ->each->withPublishedContent()
            ->sortByDesc('annee')
            ->values();

        $competences = Competence::published()->get()
            ->each->withPublishedContent()
            ->sortBy([['categorie', 'asc'], ['nom', 'asc']])
            ->values()
            ->groupBy('categorie');

        $langues = Langue::published()->get()->each->withPublishedContent();
        $centresInteret = CentreInteret::published()->get()->each->withPublishedContent();

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
