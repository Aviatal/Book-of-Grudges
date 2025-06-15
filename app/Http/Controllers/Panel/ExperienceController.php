<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use App\Models\User;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function showExperiencesForm()
    {
        $activeUsers = User::select('id', 'name')
            ->with('hero:id,user_id,name,current_experience')
            ->where('is_active', 1)
            ->get();

        return view('Panel.experience.experience_forms', compact('activeUsers'));
    }

    public function saveExperience(Request $request)
    {
        $activeUsers = Hero::select('id', 'user_id', 'name', 'current_experience', 'all_experience')
            ->get()
            ->keyBy('id')
            ->toArray();
        $commonExp = $request->get('commonExperience', 0);
        $upsertData = [];

        foreach ($request->get('heroesExperience') as $heroId => $experience) {
            $upsertData[] = [
                'id' => $heroId,
                'user_id' => $activeUsers[$heroId]['user_id'],
                'name' => $activeUsers[$heroId]['name'],
                'current_experience' => $activeUsers[$heroId]['current_experience'] + $commonExp + $experience,
                'all_experience' => $activeUsers[$heroId]['all_experience'] + $commonExp + $experience,
            ];
        }

        try {
            Hero::upsert($upsertData, ['id'], ['current_experience', 'all_experience']);
        } catch (\Throwable $exception) {
            \Log::error('Error during updating experience. Transaction rolled back.');
            \Log::error($exception);
        }
    }
}
