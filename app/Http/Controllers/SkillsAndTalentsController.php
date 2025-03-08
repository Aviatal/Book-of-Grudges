<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillsAndTalentsController extends Controller
{
    public function skillsIndex()
    {
        return view('Pages.skills');
    }

    public function getTalents()
    {
        return view('Pages.talents');
    }

    public function getSkills()
    {
        $skills = Skill::all();
        return response()->json($skills);
    }
}
