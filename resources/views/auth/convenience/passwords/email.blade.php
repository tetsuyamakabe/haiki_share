@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-forgotpassword">
            <div class="p-forgotpassword__form">
                <h1 class="c-title">{{ __('Convenience Store ForgotPassword') }}</h1>
                <convenience-forgotpassword-component></convenience-forgotpassword-component>
            </div>
        </div>
    </main>
</div>
@endsection
