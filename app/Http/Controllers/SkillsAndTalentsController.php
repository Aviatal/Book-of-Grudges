<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\Talent;
use Illuminate\Http\Request;

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

    public function getTalents()
    {
        $talents = Talent::orderBy('name')->get();
        return response()->json($talents);
    }
}
