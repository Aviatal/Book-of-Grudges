<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Hero;
use App\Models\Message;
use Illuminate\View\View;

/**
 * Wgląd superadmina do wszystkich kampanii — wyłącznie do odczytu, bez wchodzenia
 * na czat/mapę cudzej sesji (ustalone z właścicielem produktu).
 */
class SuperadminController extends Controller
{
    public function index(): View
    {
        $campaigns = Campaign::withCount('members')
            ->with('owner:id,name')
            ->orderByDesc('created_at')
            ->get();

        return view('Panel.superadmin.index', compact('campaigns'));
    }

    public function show(Campaign $campaign): View
    {
        $campaign->load('owner:id,name');

        $heroNamesByUserId = Hero::where('campaign_id', $campaign->id)->pluck('name', 'user_id');

        $members = $campaign->members()
            ->with('user:id,name,email')
            ->get()
            ->map(function ($member) use ($heroNamesByUserId) {
                $member->setAttribute('hero_name', $heroNamesByUserId->get($member->user_id));
                return $member;
            });

        $lastMessageAt = Message::where('campaign_id', $campaign->id)->max('created_at');

        return view('Panel.superadmin.show', compact('campaign', 'members', 'lastMessageAt'));
    }
}
