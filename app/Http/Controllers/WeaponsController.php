<?php

namespace App\Http\Controllers;

use App\Models\Weapon;
use Illuminate\Http\Request;

class WeaponsController extends Controller
{
    public function index()
    {
        return view('Pages.weapons.index');
    }
    public function getWeapons()
    {
        $weapons = Weapon::with('traits')->get();
        return response()->json([
            'ranged' => $weapons->where('is_ranged', 1),
            'cold' => $weapons->where('is_ranged', 0)
        ]);
    }
}
