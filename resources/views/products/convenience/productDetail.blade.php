@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-product">
            <div class="p-product__index">
                <h1 class="c-title">{{ __('ProductDetail') }}</h1>
                <convenience-productdetail-component :product="{{ $product }}" :categories="{{ $categories }}" :product_pictures="{{ $productPictures }}" :user-convenience-id="{{ $userConvenienceId }}" :product-convenience-id="{{ $productConvenienceId }}"></convenience-productdetail-component>
            </div>
        </div>
    </main>
</div>
@endsection
