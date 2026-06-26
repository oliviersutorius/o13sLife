<?php

declare(strict_types=1);

use App\Http\Controllers\CvController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CvController::class, 'index'])->name('cv');
