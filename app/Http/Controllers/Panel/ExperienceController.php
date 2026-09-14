<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use App\Models\User;
use App\Support\CurrentCampaign;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function showExperiencesForm()
    {
        $campaignId = $this->currentCampaign()->id();

        $activeUsers = User::select('id', 'name')
            ->whereHas('campaignMemberships', fn ($q) => $q->where('campaign_id', $campaignId))
            ->where('is_active', 1)
            ->get()
            ->map(function (User $user) use ($campaignId) {
                $hero = Hero::select('id', 'user_id', 'name', 'current_experience')
                    ->where('campaign_id', $campaignId)
                    ->where('user_id', $user->id)
                    ->first();

                return $hero ? ['id' => $user->id, 'name' => $user->name, 'hero' => $hero] : null;
            })
            ->filter()
            ->values();

        return view('Panel.experience.experience_forms', compact('activeUsers'));
    }

    public function saveExperience(Request $request): void
    {
        $campaignId = $this->currentCampaign()->id();
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

        $allowedHeroIds = Hero::where('campaign_id', $campaignId)
            ->whereIn('id', array_keys($notifications))
            ->pluck('id')
            ->all();

        try {
            foreach ($notifications as $heroId => $notification) {
                if (!in_array((int) $heroId, $allowedHeroIds, true)) {
                    continue;
                }
                //TODO:: Zmniejszyć ilość zapytań
                Hero::query()->where('id', $heroId)->increment('current_experience', $notification['added_amount']);
                event(new \App\Events\ExperiencePointsAdded($notification['hero_id'], $notification['added_amount'], $notification['additional_note']));
            }
        } catch (\Throwable $exception) {
            \Log::error('Error during updating experience. Transaction rolled back.');
            \Log::error($exception);
        }
    }
}
