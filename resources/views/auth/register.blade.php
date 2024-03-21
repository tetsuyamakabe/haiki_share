@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-register">
            <div class="p-register__form">
                @if(request()->query('type') === 'user')
                    <h1 class="c-title">{{ __('User Registration') }}</h1>
                @else
                    <h1 class="c-title">{{ __('Convenience Store Registration') }}</h1>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    @if(request()->query('type') === 'user')
                        <user-registration-component></user-registration-component>
                    @else
                        <convenience-registration-component></convenience-registration-component>
                    @endif
                </form>
            </div>
        </div>
    </main>
</div>
@endsection