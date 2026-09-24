@extends('Layout.master')

@section('content')
    <x-auth-card title="POTWIERDŹ E-MAIL" subtitle="Sprawdź skrzynkę, zanim otworzysz księgę">
        @if (session('resent'))
            <div class="my-4 px-3 py-2.5 text-sm text-left" role="status"
                 style="border: 1px solid var(--border-accent); background: var(--bg-inset); color: var(--gold-bright)">
                Wysłaliśmy nowy link potwierdzający na Twój adres e-mail.
            </div>
        @endif

        <p class="text-left leading-relaxed my-4" style="color: var(--text-muted)">
            Na adres <strong style="color: var(--text-body)">{{ Auth::user()->email }}</strong> wysłaliśmy wiadomość z linkiem
            potwierdzającym. Kliknij go, aby aktywować konto.
        </p>
        <p class="text-left text-sm leading-relaxed mb-5.5" style="color: var(--text-faint)">
            Nie ma wiadomości? Zajrzyj do folderu ze spamem albo poproś o nowy link.
        </p>

        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="w-full py-3 font-heading text-[13px] font-semibold tracking-[.18em] cursor-pointer"
                    style="border: 1px solid var(--border-accent); background: linear-gradient(#3a2b17,#241b10); color: var(--gold-bright)">
                WYŚLIJ LINK PONOWNIE
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-5 pt-4.5 text-sm" style="border-top: 1px solid var(--border-subtle); color: var(--text-faint)">
            @csrf
            Zły adres e-mail?
            <button type="submit" class="cursor-pointer" style="background: none; border: none; padding: 0; color: var(--gold)">Wyloguj się i zarejestruj ponownie</button>
        </form>
    </x-auth-card>
@endsection
