@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-forgotpassword">
            <div class="p-forgotpassword__form">
                <h1 class="c-title">{{ __('User ForgotPassword') }}</h1>
                <form method="POST" action="{{ route('user.password.email') }}">
                    @csrf
                    <user-forgotpassword-component></user-forgotpassword-component>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
