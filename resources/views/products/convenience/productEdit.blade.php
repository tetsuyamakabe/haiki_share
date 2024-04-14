@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-product">
            <div class="p-product__form">
                <h1 class="c-title">{{ __('ProductEdit') }}</h1>
                <convenience-productedit-component :product="{{ $product }}" :categories="{{ $categories }}" :product_pictures="{{ $productPictures }}"></convenience-productedit-component>
                <convenience-productdelete-component :product="{{ $product }}"></convenience-productdelete-component>
            </div>
        </div>
    </main>
</div>
@endsection
