@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-login">
            <div class="p-login__form">
                @if(request()->query('type') === 'user')
                    <h1 class="c-title">{{ __('User Login') }}</h1>
                @else
                    <h1 class="c-title">{{ __('Convenience Store Login') }}</h1>
                @endif
                <form method="POST" action="{{ route('login', ['type' => request()->query('type')]) }}">
                    @csrf
                    @if(request()->query('type') === 'user')
                        <user-login-component></user-login-component>
                    @else
                        <convenience-login-component></convenience-login-component>
                    @endif
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </main>
</div>
@endsection