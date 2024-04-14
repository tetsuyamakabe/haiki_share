@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-product">
            <div class="p-product__index">
                <h1 class="c-title">{{ __('Convenience Store Product Index') }}</h1>
                <convenience-productindex-component :products="{{ $products }}"></convenience-productindex-component>
            </div>
        </div>
    </main>
</div>
@endsection