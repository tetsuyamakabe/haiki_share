@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-profile">
            <div class="p-product__form">
                <h1 class="c-title">{{ __('ProductSale') }}</h1>
                <convenience-productsale-component :user="{{ $user }}" :categories="{{ $categories }}"></convenience-productsale-component>
            </div>
        </div>
    </main>
</div>
@endsection
