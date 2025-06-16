<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ExpUpdate;
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
        $commonExp = $request->get('commonExperience', 0);
        $insertData = [];

        foreach ($request->get('heroesExperience') as $heroId => $experience) {
            $insertData[] = [
                'hero_id' => $heroId,
                'read' => 1,
                'exp_amount' => $commonExp + $experience,
                'additional_note' => '',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        foreach ($request->get('heroesNotes') as $heroId => $note) {
            $insertData[$heroId]['additional_note'] = $note;
        }

        try {
            ExpUpdate::create($insertData);
        } catch (\Throwable $exception) {
            \Log::error('Error during updating experience. Transaction rolled back.');
            \Log::error($exception);
        }
    }

    public function experienceWatch(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return response()->stream(function () use ($request) {
            $user = $request->user();

            while (true) {
                $xpUpdate = ExpUpdate::where('hero_id', $user->hero->id)
                    ->where('read', false)
                    ->latest()
                    ->first();

                if ($xpUpdate) {
                    echo "data: " . json_encode([
                            'amount' => $xpUpdate->exp_amount,
                            'message' => $xpUpdate->additional_note ?? 'Dobra gra!',
                        ], JSON_THROW_ON_ERROR) . "\n\n";
                    $user->hero->update(['current_experience' => $user->hero->current_experience + $xpUpdate->exp_amount]);
                    $xpUpdate->read = true;
                    $xpUpdate->save();

                    ob_flush();
                    flush();
                }

                sleep(1);
            }

        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no'
        ]);
    }
}
