@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-register">
            <div class="p-register__form">
                <h1 class="c-title">{{ __('User Register') }}</h1>
                <user-register-component></user-register-component>
            </div>
        </div>
    </main>
</div>
@endsection