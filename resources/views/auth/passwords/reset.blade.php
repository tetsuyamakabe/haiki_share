@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-resetpassword">
            <div class="p-resetpassword__form">
                <h1 class="c-title">{{ __('User Reset Password') }}</h1>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <resetpassword-component></resetpassword-component>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
