<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SkillsAndTalentsController extends Controller
{
    public function getSkills()
    {
        return view('pages.skills');
    }

    public function getTalents()
    {
        return view('pages.talents');
    }
}
