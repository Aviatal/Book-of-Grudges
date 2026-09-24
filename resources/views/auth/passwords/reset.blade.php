@extends('Layout.master')

@section('content')
    <x-auth-card title="NOWE HASŁO" subtitle="Ustaw hasło do swojego konta">
        <form method="POST" action="{{ route('password.update') }}" class="text-left">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <label class="block mb-3.5">
                <span class="block font-heading text-[10px] tracking-[.18em] mb-1.5" style="color: var(--text-faint)">E-MAIL</span>
                <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                       class="w-full box-border px-3 py-3 font-body"
                       style="background: var(--bg-inset); border: 1px solid var(--border-default); color: var(--text-body)">
                @error('email')
                    <span class="block mt-1 text-sm" role="alert" style="color: var(--danger-text)">{{ $message }}</span>
                @enderror
            </label>

            <label class="block mb-3.5">
                <span class="block font-heading text-[10px] tracking-[.18em] mb-1.5" style="color: var(--text-faint)">NOWE HASŁO</span>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="w-full box-border px-3 py-3 font-body"
                       style="background: var(--bg-inset); border: 1px solid var(--border-default); color: var(--text-body)">
                @error('password')
                    <span class="block mt-1 text-sm" role="alert" style="color: var(--danger-text)">{{ $message }}</span>
                @enderror
            </label>

            <label class="block mb-5">
                <span class="block font-heading text-[10px] tracking-[.18em] mb-1.5" style="color: var(--text-faint)">POWTÓRZ HASŁO</span>
                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full box-border px-3 py-3 font-body"
                       style="background: var(--bg-inset); border: 1px solid var(--border-default); color: var(--text-body)">
            </label>

            <button type="submit" class="w-full py-3 font-heading text-[13px] font-semibold tracking-[.18em] cursor-pointer"
                    style="border: 1px solid var(--border-accent); background: linear-gradient(#3a2b17,#241b10); color: var(--gold-bright)">
                ZAPISZ HASŁO
            </button>
        </form>
    </x-auth-card>
@endsection
