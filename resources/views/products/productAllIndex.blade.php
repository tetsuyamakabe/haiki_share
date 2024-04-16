@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-product">
            <div class="p-product__index">
                <h1 class="c-title">{{ __('Product All Index') }}</h1>
                <product-allindex-component :products="{{ $products }}"></product-allindex-component>
            </div>
        </div>
    </main>
</div>
@endsection