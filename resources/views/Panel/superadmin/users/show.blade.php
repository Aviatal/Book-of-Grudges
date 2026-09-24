@extends('Layout.master')

@section('content')
    <div class="px-4 py-10 lg:px-10">
        <div class="max-w-[900px] mx-auto">
            <a href="{{ route('panel.superadmin.users.index') }}" class="text-sm" style="color: var(--text-faint)">← Użytkownicy</a>

            <div class="mb-8 mt-3 flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <div class="font-heading text-[10px] tracking-[.2em] mb-1.5" style="color: var(--text-faint-alt)">PANEL ADMINISTRATORA</div>
                    <h1 class="font-heading text-2xl font-bold tracking-[.08em] m-0" style="color: var(--gold)">{{ $user->name }}</h1>
                    <p class="text-sm mt-2" style="color: var(--text-faint)">
                        {{ $user->email }}
                        · {{ $user->email_verified_at ? 'zweryfikowany ' . $user->email_verified_at->format('d.m.Y') : 'e-mail niezweryfikowany' }}
                        · konto od {{ $user->created_at?->format('d.m.Y') ?? '—' }}
                        · {{ $user->is_active ? 'aktywny' : 'nieaktywny' }}
                        @if ($user->is_superadmin) · superadmin @endif
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('panel.superadmin.users.edit', $user) }}" class="px-4 py-2 font-heading text-[11px] tracking-[.15em]"
                       style="border: 1px solid var(--border-accent); background: linear-gradient(#3a2b17,#241b10); color: var(--gold-bright)">EDYTUJ</a>
                    @unless ($user->is(auth()->user()))
                        <form method="POST" action="{{ route('panel.superadmin.users.destroy', $user) }}"
                              onsubmit="return confirm('Trwale usunąć użytkownika {{ e($user->name) }} wraz z jego bohaterami i wiadomościami? Tej operacji nie można cofnąć.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 font-heading text-[11px] tracking-[.15em] cursor-pointer"
                                    style="border: 1px solid var(--danger-border); background: var(--danger-bg); color: var(--danger-text)">USUŃ</button>
                        </form>
                    @endunless
                </div>
            </div>

            @include('Panel.superadmin._alerts')

            <h2 class="font-heading text-sm tracking-[.15em] mb-3" style="color: var(--gold)">BOHATEROWIE</h2>
            <div class="flex flex-col gap-2.5 mb-8">
                @forelse ($user->heroes as $hero)
                    <a href="{{ route('panel.superadmin.heroes.show', $hero->id) }}" class="block px-4 py-3.5"
                       style="border: 1px solid var(--border-default); background: var(--bg-panel-gradient)">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-base" style="color: var(--text-body)">
                                {{ $hero->name }}
                                @if ($hero->trashed())
                                    <span class="font-heading text-[10px] tracking-[.1em] ml-2" style="color: var(--danger-text)">USUNIĘTY</span>
                                @endif
                            </span>
                            <span class="font-heading text-[11px] tracking-[.1em]" style="color: var(--gold)">{{ $hero->race }}</span>
                        </div>
                        <div class="text-sm mt-1" style="color: var(--text-faint)">
                            {{ $hero->currentProfession->name ?? '—' }}
                            · kampania: {{ $hero->campaign->name ?? '—' }}
                        </div>
                    </a>
                @empty
                    <p class="italic" style="color: var(--text-faint)">Użytkownik nie ma jeszcze bohaterów.</p>
                @endforelse
            </div>

            <h2 class="font-heading text-sm tracking-[.15em] mb-3" style="color: var(--gold)">KAMPANIE</h2>
            <div class="flex flex-col gap-2.5">
                @forelse ($user->campaignMemberships as $membership)
                    <a href="{{ route('panel.superadmin.show', $membership->campaign_id) }}" class="block px-4 py-3.5"
                       style="border: 1px solid var(--border-default); background: var(--bg-panel-gradient)">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-base" style="color: var(--text-body)">{{ $membership->campaign->name }}</span>
                            <span class="font-heading text-[11px] tracking-[.1em]" style="color: var(--gold)">{{ $membership->isGm() ? 'Mistrz Gry' : 'Gracz' }}</span>
                        </div>
                        <div class="text-sm mt-1" style="color: var(--text-faint)">dołączył {{ $membership->joined_at->format('d.m.Y') }}</div>
                    </a>
                @empty
                    <p class="italic" style="color: var(--text-faint)">Użytkownik nie należy do żadnej kampanii.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
