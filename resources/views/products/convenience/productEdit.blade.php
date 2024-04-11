@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-profile">
            <div class="p-profile__form">
                <h1 class="c-title">{{ __('ProductEdit') }}</h1>
                <form method="POST" action="{{ route('convenience.productEdit.edit', $product->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <convenience-productedit-component :product="{{ $product }}" :categories="{{ $categories }}" :product_pictures="{{ $productPictures }}"></convenience-productedit-component>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
