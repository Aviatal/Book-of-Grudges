@extends('Layout.master')

@section('content')
    @php($isSelf = $user->is(auth()->user()))
    <div class="px-4 py-10 lg:px-10">
        <div class="max-w-[600px] mx-auto">
            <a href="{{ route('panel.superadmin.users.show', $user) }}" class="text-sm" style="color: var(--text-faint)">← {{ $user->name }}</a>

            <div class="mb-8 mt-3">
                <div class="font-heading text-[10px] tracking-[.2em] mb-1.5" style="color: var(--text-faint-alt)">PANEL ADMINISTRATORA</div>
                <h1 class="font-heading text-2xl font-bold tracking-[.08em] m-0" style="color: var(--gold)">Edycja użytkownika</h1>
            </div>

            @include('Panel.superadmin._alerts')

            <form method="POST" action="{{ route('panel.superadmin.users.update', $user) }}" class="flex flex-col gap-5">
                @csrf
                @method('PUT')

                <label class="block">
                    <span class="block font-heading text-[10px] tracking-[.18em] mb-1.5" style="color: var(--text-faint-alt)">NAZWA</span>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required maxlength="255"
                           class="w-full box-border px-3 py-2 font-body text-base"
                           style="background: var(--bg-inset); border: 1px solid var(--border-default); color: var(--text-body)">
                </label>

                <label class="block">
                    <span class="block font-heading text-[10px] tracking-[.18em] mb-1.5" style="color: var(--text-faint-alt)">E-MAIL</span>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="255"
                           class="w-full box-border px-3 py-2 font-body text-base"
                           style="background: var(--bg-inset); border: 1px solid var(--border-default); color: var(--text-body)">
                    <span class="block text-sm mt-1" style="color: var(--text-faint)">Zmiana adresu unieważnia jego weryfikację — użytkownik będzie musiał potwierdzić go ponownie.</span>
                </label>

                <label class="flex items-center gap-2 text-base cursor-pointer" style="color: var(--text-muted)">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active)) @disabled($isSelf)
                           class="appearance-none w-3.5 h-3.5 inline-block border border-[var(--border-accent)] bg-[var(--bg-inset)] checked:bg-[var(--gold)] checked:border-[var(--gold)] cursor-pointer">
                    Konto aktywne
                    <span class="text-sm" style="color: var(--text-faint)">(nieaktywni nie pojawiają się na listach MG)</span>
                </label>

                <label class="flex items-center gap-2 text-base cursor-pointer" style="color: var(--text-muted)">
                    <input type="checkbox" name="is_superadmin" value="1" @checked(old('is_superadmin', $user->is_superadmin)) @disabled($isSelf)
                           class="appearance-none w-3.5 h-3.5 inline-block border border-[var(--border-accent)] bg-[var(--bg-inset)] checked:bg-[var(--gold)] checked:border-[var(--gold)] cursor-pointer">
                    Superadmin
                </label>
                @if ($isSelf)
                    <p class="text-sm -mt-3" style="color: var(--text-faint)">Własnych uprawnień i aktywności nie możesz zmienić — to zabezpieczenie przed zablokowaniem sobie dostępu.</p>
                    {{-- Wyłączone checkboxy nie są wysyłane, więc dołączamy aktualne wartości, żeby zapis ich nie wyzerował. --}}
                    <input type="hidden" name="is_active" value="{{ $user->is_active ? 1 : 0 }}">
                    <input type="hidden" name="is_superadmin" value="{{ $user->is_superadmin ? 1 : 0 }}">
                @endif

                <div class="flex items-center gap-4">
                    <button type="submit" class="px-6 py-3 font-heading text-[13px] font-semibold tracking-[.18em] cursor-pointer"
                            style="border: 1px solid var(--border-accent); background: linear-gradient(#3a2b17,#241b10); color: var(--gold-bright)">ZAPISZ</button>
                    <a href="{{ route('panel.superadmin.users.show', $user) }}" class="text-sm" style="color: var(--text-faint)">Anuluj</a>
                </div>
            </form>
        </div>
    </div>
@endsection
