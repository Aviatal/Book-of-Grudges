@extends('Layout.master')

@section('content')
    <div class="px-4 py-10 lg:px-10">
        <div class="max-w-[900px] mx-auto">
            <div class="mb-8">
                <div class="font-heading text-[10px] tracking-[.2em] mb-1.5" style="color: var(--text-faint-alt)">PANEL ADMINISTRATORA</div>
                <h1 class="font-heading text-2xl font-bold tracking-[.08em] m-0" style="color: var(--gold)">Użytkownicy</h1>
                <p class="italic mt-2" style="color: var(--text-faint)">Wszyscy użytkownicy w bazie — {{ $users->total() }}.</p>
            </div>

            @include('Panel.superadmin._alerts')

            <form method="GET" action="{{ route('panel.superadmin.users.index') }}" class="flex gap-2 mb-5">
                <input type="search" name="q" value="{{ $search }}" placeholder="Szukaj po nazwie lub e-mailu"
                       class="flex-1 min-w-0 box-border px-3 py-2 font-body text-base"
                       style="background: var(--bg-inset); border: 1px solid var(--border-default); color: var(--text-body)">
                <button type="submit" class="px-4 font-heading text-[11px] tracking-[.15em] cursor-pointer"
                        style="border: 1px solid var(--border-accent); background: linear-gradient(#3a2b17,#241b10); color: var(--gold-bright)">SZUKAJ</button>
            </form>

            <div class="flex flex-col gap-2.5">
                @forelse ($users as $user)
                    <div class="px-4 py-3.5" style="border: 1px solid var(--border-default); background: var(--bg-panel-gradient)">
                        <div class="flex items-center justify-between gap-3 flex-wrap">
                            <a href="{{ route('panel.superadmin.users.show', $user) }}" class="text-base" style="color: var(--text-body)">
                                {{ $user->name }}
                                @if ($user->is_superadmin)
                                    <span class="font-heading text-[10px] tracking-[.1em] ml-2" style="color: var(--gold)">SUPERADMIN</span>
                                @endif
                                @unless ($user->is_active)
                                    <span class="font-heading text-[10px] tracking-[.1em] ml-2" style="color: var(--danger-text)">NIEAKTYWNY</span>
                                @endunless
                            </a>
                            <div class="flex items-center gap-4 text-sm">
                                <a href="{{ route('panel.superadmin.users.show', $user) }}" style="color: var(--text-faint)">Szczegóły</a>
                                <a href="{{ route('panel.superadmin.users.edit', $user) }}" style="color: var(--gold)">Edytuj</a>
                            </div>
                        </div>
                        <div class="text-sm mt-1" style="color: var(--text-faint)">
                            {{ $user->email }}
                            · bohaterowie: {{ $user->heroes_count }}
                            · kampanie: {{ $user->campaign_memberships_count }}
                            · konto od {{ $user->created_at?->format('d.m.Y') ?? '—' }}
                            @unless ($user->email_verified_at) · e-mail niezweryfikowany @endunless
                        </div>
                    </div>
                @empty
                    <p class="italic" style="color: var(--text-faint)">Nie znaleziono użytkowników.</p>
                @endforelse
            </div>

            <div class="mt-6">{{ $users->links() }}</div>
        </div>
    </div>
@endsection
