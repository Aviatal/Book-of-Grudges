@extends('Layout.master')

@section('content')
    <x-auth-card title="ADRES POTWIERDZONY" subtitle="Twoje imię widnieje już w księdze">
        <p class="text-left leading-relaxed my-4" style="color: var(--text-muted)">
            Dziękujemy! Adres <strong style="color: var(--text-body)">{{ Auth::user()->email }}</strong> został potwierdzony.
            Możesz teraz korzystać z aplikacji.
        </p>

        <a href="{{ route('home') }}" class="block w-full py-3 font-heading text-[13px] font-semibold tracking-[.18em] no-underline"
           style="border: 1px solid var(--border-accent); background: linear-gradient(#3a2b17,#241b10); color: var(--gold-bright)">
            PRZEJDŹ DO KSIĘGI
        </a>
    </x-auth-card>
@endsection
