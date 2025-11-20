<?php

namespace App\Http\Controllers\Panel;

use App\Events\ExperiencePointsAdded;
use App\Http\Controllers\Controller;
use App\Models\HeroUpdate;
use App\Models\Hero;
use App\Models\User;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function showExperiencesForm()
    {
        $activeUsers = User::select('id', 'name')
            ->withWhereHas('hero:id,user_id,name,current_experience')
            ->where('is_active', 1)
            ->get();

        return view('Panel.experience.experience_forms', compact('activeUsers'));
    }

    public function saveExperience(Request $request)
    {
        $commonExp = $request->get('commonExperience', 0);
        $insertData = [];

        foreach ($request->get('heroesExperience') as $heroId => $experience) {
            ExperiencePointsAdded::dispatch($heroId, $commonExp + $experience);
//            event(new ExperiencePointsAdded($heroId, $commonExp + $experience));

            $insertData[] = [
                'hero_id' => $heroId,
                'type' => HeroUpdate::TYPES['EXP'],
                'read' => 0,
                'added_amount' => $commonExp + $experience,
                'additional_note' => '',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        foreach ($request->get('heroesNotes') as $heroId => $note) {
            $insertData[$heroId]['additional_note'] = $note;
        }

        try {
        } catch (\Throwable $exception) {
            \Log::error('Error during updating experience. Transaction rolled back.');
            \Log::error($exception);
        }
    }
}
