@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-forgotpassword">
            <div class="p-forgotpassword__form">
                <h1 class="c-title">{{ __('Convenience Store ForgotPassword') }}</h1>
                <form method="POST" action="{{ route('convenience.password.email') }}">
                    @csrf
                    <convenience-forgotpassword-component></convenience-forgotpassword-component>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
