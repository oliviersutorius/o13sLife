<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function edit(): View
    {
        return view('admin.profil');
    }
}
