@extends('Layout.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="background-color: #333; color: #D4A373; border: none; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);">
                    <div class="card-header" style="background-color: #222; color: #D4A373; text-align: center; padding: 1rem;">{{ __('Reset Password') }}</div>

                    <div class="card-body" style="padding: 2rem;">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert" style="background-color: #4CAF50; color: white; text-align: center;">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-12" style="text-align: center;">
                                    <label for="email" class="col-form-label" style="color: #D4A373;">E-mail</label>
                                </div>
                                <div class="col-12" style="text-align: center;">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus style="background-color: #555; color: #fff; border: 1px solid #666; padding: 0.5rem; border-radius: 4px; width: 70%; margin: 0 auto; text-align: center;">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert" style="color: #ff6666;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-12" style="text-align: center;">
                                    <button type="submit" class="btn btn-primary" style="background-color: #D4A373; color: #222; border: none; padding: 0.75rem 1.5rem; border-radius: 4px;">
                                        Wyślij link do resetu hasła
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
