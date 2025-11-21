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

    public function saveExperience(Request $request): void
    {
        $commonExp = $request->get('commonExperience', 0);
        $notifications = [];

        foreach ($request->get('heroesExperience') as $heroId => $experience) {
            $notifications[$heroId] = [
                'hero_id' => $heroId,
                'added_amount' => $commonExp + $experience,
                'additional_note' => '',
            ];
        }
        foreach ($request->get('heroesNotes') as $heroId => $note) {
            $notifications[$heroId]['additional_note'] = $note;
        }

        try {
            foreach ($notifications as $notification) {
                event(new \App\Events\ExperiencePointsAdded($notification['hero_id'], $notification['added_amount'], $notification['additional_note']));
            }
        } catch (\Throwable $exception) {
            \Log::error('Error during updating experience. Transaction rolled back.');
            \Log::error($exception);
        }
    }
}
