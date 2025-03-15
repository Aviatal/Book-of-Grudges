<?php

namespace App\Http\Controllers;

use App\Models\Armor;
use App\Models\Weapon;
use Illuminate\Http\Request;

class ArmorsController extends Controller
{
    public function index()
    {
        return view('Pages.armors.index');
    }
    public function getArmors(Request $request)
    {
        $armors = Armor::with('locations')->get();
        if ($request->has('grouped')) {
            return response()->json($armors->select('name', 'category', 'id')->toArray());
        }
        return response()->json([
            'leather' => $armors->where('category', 'SKÓRZANA'),
            'mail' => $armors->where('category', 'KOLCZA'),
            'plate' => $armors->where('category', 'PŁYTOWA'),
        ]);
    }
}
