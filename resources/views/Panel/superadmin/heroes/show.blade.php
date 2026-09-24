@extends('Layout.master')

@section('content')
    @php
        $description = $hero->description;
        $descriptionRows = $description ? array_filter([
            'Wiek' => $description->age,
            'Płeć' => $description->gender,
            'Kolor oczu' => $description->eye_color,
            'Kolor włosów' => $description->hair_color,
            'Znak gwiezdny' => $description->star_sign,
            'Waga' => $description->weight,
            'Wzrost' => $description->height,
            'Rodzeństwo' => $description->siblings,
            'Miejsce urodzenia' => $description->place_of_birth,
            'Znaki szczególne' => $description->distinguishing_signs,
        ], fn ($value) => $value !== null && $value !== '') : [];
        $weapons = $hero->coldWeapons->concat($hero->rangedWeapons);
    @endphp
    <div class="px-4 py-10 lg:px-10">
        <div class="max-w-[900px] mx-auto">
            <a href="{{ route('panel.superadmin.users.show', $hero->user_id) }}" class="text-sm" style="color: var(--text-faint)">← {{ $hero->user->name ?? 'Użytkownik' }}</a>

            <div class="mb-8 mt-3">
                <div class="font-heading text-[10px] tracking-[.2em] mb-1.5" style="color: var(--text-faint-alt)">PANEL ADMINISTRATORA · TYLKO DO ODCZYTU</div>
                <h1 class="font-heading text-2xl font-bold tracking-[.08em] m-0" style="color: var(--gold)">
                    {{ $hero->name }}
                    @if ($hero->trashed())
                        <span class="font-heading text-xs tracking-[.1em] ml-2" style="color: var(--danger-text)">USUNIĘTY {{ $hero->deleted_at->format('d.m.Y') }}</span>
                    @endif
                </h1>
                <p class="text-sm mt-2" style="color: var(--text-faint)">
                    {{ $hero->race }} · {{ $hero->currentProfession->name ?? '—' }}
                    @if ($hero->previousProfession) (wcześniej: {{ $hero->previousProfession->name }}) @endif
                    · kampania: {{ $hero->campaign->name ?? '—' }}
                    · gracz: {{ $hero->user->name ?? '—' }} ({{ $hero->user->email ?? '—' }})
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mb-8">
                @foreach ([
                    'PD (obecne / łącznie)' => ($hero->current_experience ?? 0) . ' / ' . ($hero->all_experience ?? 0),
                    'Żywotność (rany)' => $hero->current_wounds,
                    'Punkty szczęścia' => $hero->fortune_points,
                    'Punkty przeznaczenia' => $hero->fate_points,
                ] as $label => $value)
                    <div class="px-3 py-3" style="border: 1px solid var(--border-default); background: var(--bg-panel-gradient)">
                        <div class="font-heading text-[10px] tracking-[.15em]" style="color: var(--text-faint-alt)">{{ $label }}</div>
                        <div class="text-lg mt-1" style="color: var(--text-body)">{{ $value }}</div>
                    </div>
                @endforeach
            </div>

            <h2 class="font-heading text-sm tracking-[.15em] mb-3" style="color: var(--gold)">CECHY</h2>
            <div class="grid grid-cols-3 sm:grid-cols-5 gap-2.5 mb-8">
                @forelse ($hero->characteristic as $characteristic)
                    <div class="px-3 py-2.5" style="border: 1px solid var(--border-default); background: var(--bg-panel-gradient)">
                        <div class="text-sm" style="color: var(--text-faint)">{{ $characteristic->name }}</div>
                        <div class="text-lg" style="color: var(--text-body)">
                            {{ (int) $characteristic->pivot->start_value + (int) $characteristic->pivot->advancement }}
                            <span class="text-xs" style="color: var(--text-faint-alt)">({{ $characteristic->pivot->start_value }} + {{ (int) $characteristic->pivot->advancement }})</span>
                        </div>
                    </div>
                @empty
                    <p class="italic col-span-full" style="color: var(--text-faint)">Brak cech.</p>
                @endforelse
            </div>

            @if ($descriptionRows)
                <h2 class="font-heading text-sm tracking-[.15em] mb-3" style="color: var(--gold)">OPIS</h2>
                <div class="px-4 py-3.5 mb-8 text-sm grid sm:grid-cols-2 gap-x-6 gap-y-1" style="border: 1px solid var(--border-default); background: var(--bg-panel-gradient); color: var(--text-body)">
                    @foreach ($descriptionRows as $label => $value)
                        <div><span style="color: var(--text-faint)">{{ $label }}:</span> {{ $value }}</div>
                    @endforeach
                </div>
            @endif

            <h2 class="font-heading text-sm tracking-[.15em] mb-3" style="color: var(--gold)">UMIEJĘTNOŚCI</h2>
            <div class="px-4 py-3.5 mb-8 text-sm" style="border: 1px solid var(--border-default); background: var(--bg-panel-gradient); color: var(--text-body)">
                @forelse ($hero->skills as $skill)
                    <div>
                        {{ $skill->name }}@if ($skill->pivot->additional_skill_name) ({{ $skill->pivot->additional_skill_name }})@endif
                        <span style="color: var(--text-faint)">— {{ $skill->pivot->second_level ? '+20' : ($skill->pivot->first_level ? '+10' : 'podstawowa') }}</span>
                    </div>
                @empty
                    <span class="italic" style="color: var(--text-faint)">Brak umiejętności.</span>
                @endforelse
            </div>

            <h2 class="font-heading text-sm tracking-[.15em] mb-3" style="color: var(--gold)">ZDOLNOŚCI</h2>
            <div class="px-4 py-3.5 mb-8 text-sm" style="border: 1px solid var(--border-default); background: var(--bg-panel-gradient); color: var(--text-body)">
                @forelse ($hero->talents as $talent)
                    <div>{{ $talent->name }}@if ($talent->pivot->additional_talent_name) ({{ $talent->pivot->additional_talent_name }})@endif</div>
                @empty
                    <span class="italic" style="color: var(--text-faint)">Brak zdolności.</span>
                @endforelse
            </div>

            <h2 class="font-heading text-sm tracking-[.15em] mb-3" style="color: var(--gold)">EKWIPUNEK</h2>
            <div class="px-4 py-3.5 text-sm flex flex-col gap-1" style="border: 1px solid var(--border-default); background: var(--bg-panel-gradient); color: var(--text-body)">
                <div>
                    <span style="color: var(--text-faint)">Sakiewka:</span>
                    {{ $hero->gold_crowns }} zk · {{ $hero->silver_shillings }} s · {{ $hero->brass_pennies }} p
                </div>
                <div>
                    <span style="color: var(--text-faint)">Broń:</span>
                    {{ $weapons->isEmpty() ? '—' : $weapons->pluck('name')->implode(', ') }}
                </div>
                <div>
                    <span style="color: var(--text-faint)">Pancerz:</span>
                    {{ $hero->armors->isEmpty() ? '—' : $hero->armors->pluck('name')->implode(', ') }}
                </div>
                <div>
                    <span style="color: var(--text-faint)">Przedmioty:</span>
                    @forelse ($hero->inventory as $item)
                        {{ $item->name }}@if ($item->description) ({{ $item->description }})@endif{{ $loop->last ? '' : ',' }}
                    @empty
                        —
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
