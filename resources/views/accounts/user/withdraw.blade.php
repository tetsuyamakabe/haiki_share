@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-withdraw">
            <div class="p-withdraw__form">
                <h1 class="c-title">{{ __('User Withdraw') }}</h1>
                <form method="POST" action="{{ route('user.withdraw') }}">
                    @csrf
                    <user-withdraw-component></user-withdraw-component>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
