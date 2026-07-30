<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Tableau de bord du back-office (page d'accueil après connexion).
 */
class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard');
    }
}
