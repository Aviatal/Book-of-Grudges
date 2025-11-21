<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use App\Models\HeroUpdate;
use App\Models\User;
use Illuminate\Http\Request;

class FortunePointsController extends Controller
{
    public function getFortunePointsManagement()
    {
        $activeUsers = User::select('id', 'name')
            ->withWhereHas('hero:id,user_id,name')
            ->where('is_active', 1)
            ->get();

        return view('Panel.fortune-points.fp_form', compact('activeUsers'));
    }

    public function assignFortunePoint(Request $request): void
    {
        $heroId = $request->get('heroId');
        Hero::query()->where('id', $heroId)->increment('fortune_points');
        event(new \App\Events\FortunePointsAdded($heroId));
    }
}
