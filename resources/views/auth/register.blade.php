@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-register">
            <div class="p-register__form">
                <h1 class="c-title">{{ __('利用者側ユーザー登録') }}</h1>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <register-component></register-component>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection