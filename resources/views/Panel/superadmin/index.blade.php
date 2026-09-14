@extends('Layout.master')

@section('content')
    <div class="px-4 py-10 lg:px-10">
        <div class="max-w-[900px] mx-auto">
            <div class="mb-8">
                <div class="font-heading text-[10px] tracking-[.2em] mb-1.5" style="color: var(--text-faint-alt)">PANEL ADMINISTRATORA</div>
                <h1 class="font-heading text-2xl font-bold tracking-[.08em] m-0" style="color: var(--gold)">Wszystkie kampanie</h1>
                <p class="italic mt-2" style="color: var(--text-faint)">Widok tylko do odczytu — bez wglądu w mapę, czat ani edycji bohaterów.</p>
            </div>

            <div class="flex flex-col gap-2.5">
                @forelse ($campaigns as $campaign)
                    <a href="{{ route('panel.superadmin.show', $campaign) }}"
                       class="block px-4 py-3.5" style="border: 1px solid var(--border-default); background: var(--bg-panel-gradient)">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-base" style="color: var(--text-body)">{{ $campaign->name }}</span>
                            <span class="font-heading text-[11px] tracking-[.1em]" style="color: var(--gold)">Członkowie: {{ $campaign->members_count }}</span>
                        </div>
                        <div class="text-sm mt-1" style="color: var(--text-faint)">
                            MG: {{ $campaign->owner->name ?? '—' }} · założona {{ $campaign->created_at->format('d.m.Y') }}
                        </div>
                    </a>
                @empty
                    <p class="italic" style="color: var(--text-faint)">Nie ma jeszcze żadnych kampanii.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
