@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-product">
            <div class="p-product__index">
                <h1 class="c-title">{{ __('User Product Index') }}</h1>
                <user-productindex-component></user-productindex-component>
            </div>
        </div>
    </main>
</div>
@endsection

<!-- 「出品しているコンビニのある都道府県」「価格」「賞味期限切れかどうか」の条件で絞り込みができること -->