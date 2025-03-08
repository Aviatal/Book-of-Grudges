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
    public function getArmors()
    {
        $armors = Armor::with('locations')->get();
        return response()->json([
            'leather' => $armors->where('category', 'SKÓRZANA'),
            'mail' => $armors->where('category', 'KOLCZA'),
            'plate' => $armors->where('category', 'PŁYTOWA'),
        ]);
    }
}
