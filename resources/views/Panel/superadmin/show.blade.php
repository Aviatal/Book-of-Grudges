@extends('Layout.master')

@section('content')
    <div class="px-4 py-10 lg:px-10">
        <div class="max-w-[900px] mx-auto">
            <a href="{{ route('panel.superadmin.index') }}" class="text-sm" style="color: var(--text-faint)">← Wszystkie kampanie</a>

            <div class="mb-8 mt-3">
                <div class="font-heading text-[10px] tracking-[.2em] mb-1.5" style="color: var(--text-faint-alt)">PANEL ADMINISTRATORA · TYLKO DO ODCZYTU</div>
                <h1 class="font-heading text-2xl font-bold tracking-[.08em] m-0" style="color: var(--gold)">{{ $campaign->name }}</h1>
                <p class="text-sm mt-2" style="color: var(--text-faint)">
                    MG: {{ $campaign->owner->name ?? '—' }} · założona {{ $campaign->created_at->format('d.m.Y') }}
                    · ostatnia wiadomość na czacie: {{ $lastMessageAt ? \Illuminate\Support\Carbon::parse($lastMessageAt)->format('d.m.Y H:i') : 'brak' }}
                </p>
            </div>

            <div class="flex flex-col gap-2.5">
                @foreach ($members as $member)
                    <div class="px-4 py-3.5" style="border: 1px solid var(--border-default); background: var(--bg-panel-gradient)">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-base" style="color: var(--text-body)">{{ $member->user->name }}</span>
                            <span class="font-heading text-[11px] tracking-[.1em]" style="color: var(--gold)">{{ $member->isGm() ? 'Mistrz Gry' : 'Gracz' }}</span>
                        </div>
                        <div class="text-sm mt-1" style="color: var(--text-faint)">
                            {{ $member->user->email }}
                            @if ($member->hero_name) · bohater: {{ $member->hero_name }} @endif
                            · dołączył {{ $member->joined_at->format('d.m.Y') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
