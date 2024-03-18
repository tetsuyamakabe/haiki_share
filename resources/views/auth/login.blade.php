@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-login">
            <div class="p-login__form">
                <h1 class="c-title">{{ __('User Login') }}</h1>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <login-component></login-component>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection