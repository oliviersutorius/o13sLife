<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Vue hébergeant le composant Livewire `Competence\Index` (CRUD de la rubrique).
 */
class CompetenceController extends Controller
{
    public function index(): View
    {
        return view('admin.competence');
    }
}
