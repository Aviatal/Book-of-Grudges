<?php

namespace App\Http\Controllers;

use App\Models\Weapon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class WeaponsController extends Controller
{
    public function index()
    {
        return view('Pages.weapons.index');
    }

    public function getWeapons(Request $request): \Illuminate\Http\JsonResponse
    {
        $weapons = Weapon::with('traits')
            ->when($request->query('search'), function (Builder $query) use ($request) {
                $query->where('name', 'like', '%' . $request->query('search') . '%');
            })
            ->get();
        if ($request->has('grouped')) {
            return response()->json($weapons->select('name', 'id')->toArray());
        }
        return response()->json([
            'ranged' => $weapons->where('is_ranged', 1),
            'cold' => $weapons->where('is_ranged', 0)
        ]);
    }
}
