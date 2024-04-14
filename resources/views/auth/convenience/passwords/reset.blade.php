@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-resetpassword">
            <div class="p-resetpassword__form">
                <h1 class="c-title">{{ __('Convenience Store Reset Password') }}</h1>
                <convenience-resetpassword-component></convenience-resetpassword-component>
            </div>
        </div>
    </main>
</div>
@endsection
