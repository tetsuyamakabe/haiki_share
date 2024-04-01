@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-mypage">
            <h1 class="c-title">{{ __('Convenience Store MyPage') }}</h1>
            <!-- プロフィール編集ボタンを表示する（ボタンクリックで画面遷移する） -->
            <a href="{{ route('convenience.profile.show') }}" class="c-button">{{ __('Edit Profile') }}</a>
        </div>
    </main>
</div>
@endsection


<!-- 商品出品ボタンを表示する（ボタンクリックで画面遷移する） -->
<!-- 出品した商品を一覧の最新5件を表示する（「全件表示」をクリックすると一覧画面に遷移する） -->
<!-- 購入された商品の最新5件をする（「全件表示」をクリックすると一覧画面に遷移する） -->