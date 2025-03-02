<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SkillsAndTalentsController extends Controller
{
    public function getSkills()
    {
        return view('Pages.skills');
    }

    public function getTalents()
    {
        return view('Pages.talents');
    }
}
