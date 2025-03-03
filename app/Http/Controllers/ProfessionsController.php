<?php

namespace App\Http\Controllers;

use App\Models\Profession;
use Illuminate\Http\Request;

class ProfessionsController extends Controller
{
    public function getProfessions()
    {
        return response()->json(
            Profession::all()->map(function ($profession) {
                return ['text' => $profession->name, 'id' => $profession->id];
            })
        );
    }
}
