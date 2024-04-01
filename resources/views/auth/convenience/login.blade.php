@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-login">
            <div class="p-login__form">
                <h1 class="c-title">{{ __('Convenience Store Login') }}</h1>
                <form method="POST" action="{{ route('convenience.login') }}">
                    @csrf
                    <convenience-login-component></convenience-login-component>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection