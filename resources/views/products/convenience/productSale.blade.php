@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-profile">
            <div class="p-profile__form">
                <h1 class="c-title">{{ __('ProductSale') }}</h1>
                <form method="POST" action="{{ route('convenience.productSale.create', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <convenience-productsale-component :user="{{ $user }}" :categories="{{ $categories }}"></convenience-productsale-component>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
