<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CentreInteretController;
use App\Http\Controllers\Admin\CompetenceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\FormationController;
use App\Http\Controllers\Admin\LangueController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\CvController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CvController::class, 'index'])->name('cv');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login']);
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('profil', [ProfilController::class, 'edit'])->name('profil.edit');
        Route::get('experiences', [ExperienceController::class, 'index'])->name('experience.index');
        Route::get('formations', [FormationController::class, 'index'])->name('formation.index');
        Route::get('competences', [CompetenceController::class, 'index'])->name('competence.index');
        Route::get('langues', [LangueController::class, 'index'])->name('langue.index');
        Route::get('centres-interet', [CentreInteretController::class, 'index'])->name('centre-interet.index');
    });
});
