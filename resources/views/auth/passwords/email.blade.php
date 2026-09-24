@extends('Layout.master')

@section('content')
    <x-auth-card title="RESET HASŁA" subtitle="Podaj adres, a wyślemy Ci link do ustawienia nowego hasła">
        @if (session('status'))
            <div class="mb-4 px-3 py-2.5 text-sm text-left" role="status"
                 style="border: 1px solid var(--border-accent); background: var(--bg-inset); color: var(--gold-bright)">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="text-left">
            @csrf

            <label class="block mb-5">
                <span class="block font-heading text-[10px] tracking-[.18em] mb-1.5" style="color: var(--text-faint)">E-MAIL</span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                       class="w-full box-border px-3 py-3 font-body"
                       style="background: var(--bg-inset); border: 1px solid var(--border-default); color: var(--text-body)">
                @error('email')
                    <span class="block mt-1 text-sm" role="alert" style="color: var(--danger-text)">{{ $message }}</span>
                @enderror
            </label>

            <button type="submit" class="w-full py-3 font-heading text-[13px] font-semibold tracking-[.18em] cursor-pointer"
                    style="border: 1px solid var(--border-accent); background: linear-gradient(#3a2b17,#241b10); color: var(--gold-bright)">
                WYŚLIJ LINK
            </button>
        </form>

        <div class="mt-5 pt-4.5 text-sm" style="border-top: 1px solid var(--border-subtle); color: var(--text-faint)">
            Pamiętasz hasło? <a href="{{ route('login') }}" style="color: var(--gold)">Zaloguj się</a>
        </div>
    </x-auth-card>
@endsection
