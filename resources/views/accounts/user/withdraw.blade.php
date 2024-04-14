@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-withdraw">
            <div class="p-withdraw__form">
                <h1 class="c-title">{{ __('User Withdraw') }}</h1>
                <user-withdraw-component :user="{{ $user }}"></user-withdraw-component>
            </div>
        </div>
    </main>
</div>
@endsection