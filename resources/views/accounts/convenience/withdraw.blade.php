@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-withdraw">
            <div class="p-withdraw__form">
                <h1 class="c-title">{{ __('Convenience Store Withdraw') }}</h1>
                <form method="POST" action="{{ route('convenience.withdraw', ['userId' => $user->id]) }}">
                    @csrf
                    <convenience-withdraw-component  :user="{{ $user }}"></convenience-withdraw-component>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection