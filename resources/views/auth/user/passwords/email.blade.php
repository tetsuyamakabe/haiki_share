@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-forgotpassword">
            <div class="p-forgotpassword__form">
                <h1 class="c-title">{{ __('User ForgotPassword') }}</h1>
                <user-forgotpassword-component></user-forgotpassword-component>
            </div>
        </div>
    </main>
</div>
@endsection
