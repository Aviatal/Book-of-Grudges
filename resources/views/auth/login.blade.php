@extends('Layout.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="background-color: #333; color: #D4A373; border: none; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);">
                    <div class="card-header" style="background-color: #222; color: #D4A373; text-align: center; padding: 1rem;">
                        Zaloguj
                    </div>

                    <div class="card-body" style="padding: 2rem;">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-12" style="text-align: center;">
                                    <label for="email" class="col-form-label" style="color: #D4A373;">E-mail</label>
                                </div>
                                <div class="col-12" style="text-align: center;">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus style="background-color: #555; color: #fff; border: 1px solid #666; padding: 0.5rem; border-radius: 4px; width: 70%; margin: 0 auto;">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert" style="color: #ff6666;">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12" style="text-align: center;">
                                    <label for="password" class="col-form-label" style="color: #D4A373;">{{ __('Password') }}</label>
                                </div>
                                <div class="col-12" style="text-align: center;">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" style="background-color: #555; color: #fff; border: 1px solid #666; padding: 0.5rem; border-radius: 4px; width: 70%; margin: 0 auto;">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert" style="color: #ff6666;">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12" style="text-align: center;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="background-color: #444; border: 1px solid #D4A373; margin: 0 auto;">
                                        <label class="form-check-label" for="remember" style="color: #D4A373;">
                                             Zapamiętaj mnie
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-12" style="text-align: center;">
                                    <button type="submit" class="btn btn-primary" style="background-color: #D4A373; color: #222; border: none; padding: 0.75rem 1.5rem; border-radius: 4px;">
                                        Zaloguj
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}" style="color: #D4A373;">
                                            Zapomniałem hasło
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <hr style="border-top: 1px solid #666; margin-top: 2rem;">
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p style="color: #D4A373; font-size: 0.9rem;">Nie masz jeszcze konta? <a href="{{ route('register') }}" style="text-decoration: none; color: #D4A373;">Zarejestruj się</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
