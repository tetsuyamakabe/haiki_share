@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-register">
            <div class="p-register__form">
                <h1 class="c-title">{{ __('User Register') }}</h1>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <user-register-component></user-register-component>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection