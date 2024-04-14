@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-register">
            <div class="p-register__form">
                <h1 class="c-title">{{ __('Convenience Store Register') }}</h1>
                <convenience-register-component></convenience-register-component>
            </div>
        </div>
    </main>
</div>
@endsection