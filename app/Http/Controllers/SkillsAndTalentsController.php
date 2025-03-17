<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\Talent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkillsAndTalentsController extends Controller
{
    public function skillsIndex()
    {
        return view('Pages.skills');
    }

    public function talentsIndex()
    {
        return view('Pages.talents');
    }

    public function getSkills()
    {
        $skills = Skill::all();
        return response()->json($skills);
    }

    public function getTalents(Request $request)
    {
        $talents = Talent::orderBy('name');
        if ($request->has('withoutOwned')) {
            $heroTalents = DB::table('hero_talent')
                ->select('talent_id')
                ->where('hero_id', $request->has('withoutOwned'))
                ->get()
                ->pluck('talent_id')
                ->toArray();
            return response()->json($talents->whereNotIn('id', $heroTalents)->get());
        }
        return response()->json($talents->get());
    }
}
