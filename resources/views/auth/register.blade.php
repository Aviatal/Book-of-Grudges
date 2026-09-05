@extends('Layout.master')

@section('content')
    <div class="flex items-center justify-center px-8 py-16">
        <div class="relative w-full max-w-[440px] p-1" style="border: 1px solid var(--border-frame); background: var(--bg-inset-alt)">
            <span class="absolute -top-px -left-px w-2.5 h-2.5" style="border-top: 2px solid var(--gold); border-left: 2px solid var(--gold)"></span>
            <span class="absolute -top-px -right-px w-2.5 h-2.5" style="border-top: 2px solid var(--gold); border-right: 2px solid var(--gold)"></span>
            <span class="absolute -bottom-px -left-px w-2.5 h-2.5" style="border-bottom: 2px solid var(--gold); border-left: 2px solid var(--gold)"></span>
            <span class="absolute -bottom-px -right-px w-2.5 h-2.5" style="border-bottom: 2px solid var(--gold); border-right: 2px solid var(--gold)"></span>

            <div class="text-center px-8 pt-9 pb-7.5" style="border: 1px solid var(--border-subtle); background: var(--bg-panel-gradient)">
                <div class="w-20 h-20 mx-auto mb-4 rounded-lg overflow-hidden" style="box-shadow: 0 4px 14px rgba(0,0,0,.7)">
                    <img src="{{ asset('images/logo-mark.png') }}" alt="Book of Grudges" class="w-full h-full object-cover">
                </div>
                <h1 class="font-heading text-[22px] font-bold tracking-[.14em] m-0" style="color: var(--gold)">ZAŁÓŻ KONTO</h1>
                <div class="italic my-4" style="color: var(--text-faint)">Nowy wpis w księdze urazów</div>

                <form method="POST" action="{{ route('register') }}" class="text-left">
                    @csrf

                    <label class="block mb-3.5">
                        <span class="block font-heading text-[10px] tracking-[.18em] mb-1.5" style="color: var(--text-faint)">{{ __('Name') }}</span>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                               class="w-full box-border px-3 py-3 font-body"
                               style="background: var(--bg-inset); border: 1px solid var(--border-default); color: var(--text-body)">
                        @error('name')
                            <span class="block mt-1 text-sm" role="alert" style="color: var(--danger-text)">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="block mb-3.5">
                        <span class="block font-heading text-[10px] tracking-[.18em] mb-1.5" style="color: var(--text-faint)">E-MAIL</span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                               class="w-full box-border px-3 py-3 font-body"
                               style="background: var(--bg-inset); border: 1px solid var(--border-default); color: var(--text-body)">
                        @error('email')
                            <span class="block mt-1 text-sm" role="alert" style="color: var(--danger-text)">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="block mb-3.5">
                        <span class="block font-heading text-[10px] tracking-[.18em] mb-1.5" style="color: var(--text-faint)">{{ __('Password') }}</span>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                               class="w-full box-border px-3 py-3 font-body"
                               style="background: var(--bg-inset); border: 1px solid var(--border-default); color: var(--text-body)">
                        @error('password')
                            <span class="block mt-1 text-sm" role="alert" style="color: var(--danger-text)">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="block mb-5">
                        <span class="block font-heading text-[10px] tracking-[.18em] mb-1.5" style="color: var(--text-faint)">{{ __('Confirm Password') }}</span>
                        <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                               class="w-full box-border px-3 py-3 font-body"
                               style="background: var(--bg-inset); border: 1px solid var(--border-default); color: var(--text-body)">
                    </label>

                    <button type="submit" class="w-full py-3 font-heading text-[13px] font-semibold tracking-[.18em] cursor-pointer"
                            style="border: 1px solid var(--border-accent); background: linear-gradient(#3a2b17,#241b10); color: var(--gold-bright)">
                        {{ __('Register') }}
                    </button>
                </form>

                <div class="mt-5 pt-4.5 text-sm" style="border-top: 1px solid var(--border-subtle); color: var(--text-faint)">
                    Masz już konto? <a href="{{ route('login') }}" style="color: var(--gold)">Zaloguj się</a>
                </div>
            </div>
        </div>
    </div>
@endsection
