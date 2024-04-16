@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-product">
            <div class="p-product__index">
                <h1 class="c-title">{{ __('ProductDetail') }}</h1>
                <user-productdetail-component :product="{{ $product }}" :categories="{{ $categories }}" :product_pictures="{{ $productPictures }}"></user-productdetail-component>
            </div>
        </div>
    </main>
</div>
@endsection
