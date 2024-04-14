@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-withdraw">
            <div class="p-withdraw__form">
                <h1 class="c-title">{{ __('Convenience Store Withdraw') }}</h1>
                <convenience-withdraw-component  :user="{{ $user }}"></convenience-withdraw-component>
            </div>
        </div>
    </main>
</div>
@endsection