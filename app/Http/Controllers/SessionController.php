<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\HeroesRepository;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index(Request $request, HeroesRepository $heroesRepository)
    {
        $heroId = $heroesRepository->getHero($request->user()->getAuthIdentifier())->id;
        return view('Pages.session.index', compact('heroId'));
    }
}
