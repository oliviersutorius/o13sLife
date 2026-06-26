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
        $profil = Profil::where('is_published', true)->first();

        $experiences = Experience::published()->ordered()->get();
        $formations = Formation::published()->orderBy('annee', 'desc')->get();
        $competences = Competence::published()->orderBy('categorie')->orderBy('nom')->get()
            ->groupBy('categorie');
        $langues = Langue::published()->get();
        $centresInteret = CentreInteret::published()->get();

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
