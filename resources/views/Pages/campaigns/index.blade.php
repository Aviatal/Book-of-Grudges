@extends('Layout.master')

@section('content')
    <div class="px-4 py-10 lg:px-10">
        <div class="max-w-[720px] mx-auto">
            <div class="mb-8">
                <div class="font-heading text-[10px] tracking-[.2em] mb-1.5" style="color: var(--text-faint-alt)">KSIĘGA KAMPANII</div>
                <h1 class="font-heading text-2xl font-bold tracking-[.08em] m-0" style="color: var(--gold)">Twoje kampanie</h1>
            </div>

            @if ($errors->any())
                <div class="mb-6 px-4 py-3 text-sm" style="border: 1px solid var(--danger-text); color: var(--danger-text); background: var(--bg-inset)">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if ($memberships->isNotEmpty())
                <div class="mb-10 flex flex-col gap-2.5">
                    @foreach ($memberships as $membership)
                        <form method="POST" action="{{ route('campaigns.switch', $membership->campaign_id) }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3.5 flex items-center justify-between gap-3"
                                    style="border: 1px solid var(--border-default); background: var(--bg-panel-gradient); cursor: pointer">
                                <span>
                                    <span class="block text-base" style="color: var(--text-body)">{{ $membership->campaign->name }}</span>
                                    <span class="block text-sm mt-0.5" style="color: var(--text-faint)">{{ $membership->isGm() ? 'Mistrz Gry' : 'Gracz' }}</span>
                                </span>
                                <span class="font-heading text-[11px] tracking-[.14em]" style="color: var(--gold)">WEJDŹ →</span>
                            </button>
                        </form>
                    @endforeach
                </div>
            @else
                <p class="mb-10 italic" style="color: var(--text-faint)">Nie należysz jeszcze do żadnej kampanii — załóż własną albo dołącz kodem zaproszenia od swojego MG.</p>
            @endif

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="p-5" style="border: 1px solid var(--border-subtle); background: var(--bg-inset-alt)">
                    <h2 class="font-heading text-sm font-bold tracking-[.1em] mb-3.5" style="color: var(--text-body)">Załóż nową kampanię</h2>
                    <form method="POST" action="{{ route('campaigns.store') }}">
                        @csrf
                        <label class="block mb-3.5">
                            <span class="block font-heading text-[10px] tracking-[.18em] mb-1.5" style="color: var(--text-faint)">NAZWA KAMPANII</span>
                            <input type="text" name="name" required maxlength="80" value="{{ old('name') }}"
                                   class="w-full box-border px-3 py-2.5 font-body"
                                   style="background: var(--bg-inset); border: 1px solid var(--border-default); color: var(--text-body)">
                        </label>
                        <button type="submit" class="w-full py-2.5 font-heading text-[12px] font-semibold tracking-[.16em] cursor-pointer"
                                style="border: 1px solid var(--border-accent); background: linear-gradient(#3a2b17,#241b10); color: var(--gold-bright)">
                            ZOSTAŃ MISTRZEM GRY
                        </button>
                    </form>
                </div>

                <div class="p-5" style="border: 1px solid var(--border-subtle); background: var(--bg-inset-alt)">
                    <h2 class="font-heading text-sm font-bold tracking-[.1em] mb-3.5" style="color: var(--text-body)">Dołącz do kampanii</h2>
                    <form method="POST" action="{{ route('campaigns.join') }}">
                        @csrf
                        <label class="block mb-3.5">
                            <span class="block font-heading text-[10px] tracking-[.18em] mb-1.5" style="color: var(--text-faint)">KOD ZAPROSZENIA</span>
                            <input type="text" name="code" required maxlength="32" value="{{ old('code') }}"
                                   class="w-full box-border px-3 py-2.5 font-body"
                                   style="background: var(--bg-inset); border: 1px solid var(--border-default); color: var(--text-body)">
                        </label>
                        <button type="submit" class="w-full py-2.5 font-heading text-[12px] font-semibold tracking-[.16em] cursor-pointer"
                                style="border: 1px solid var(--border-accent); background: linear-gradient(#3a2b17,#241b10); color: var(--gold-bright)">
                            DOŁĄCZ JAKO GRACZ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
