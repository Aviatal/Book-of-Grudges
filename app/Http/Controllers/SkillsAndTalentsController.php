<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\Talent;
use Illuminate\Database\Eloquent\Builder;
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

    public function getSkills(Request $request): \Illuminate\Http\JsonResponse
    {
        $skills = Skill::query()
            ->when($request->query('search'), function (Builder $query) use ($request) {
                $query->where('name', 'like', '%' . $request->query('search') . '%');
            })
            ->get();
        return response()->json($skills);
    }

    public function getTalents(Request $request): \Illuminate\Http\JsonResponse
    {
        $talents = Talent::query()
            ->when($request->query('search'), function (Builder $query) use ($request) {
                $query->where('name', 'like', '%' . $request->query('search') . '%');
            })
            ->orderBy('name');
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
