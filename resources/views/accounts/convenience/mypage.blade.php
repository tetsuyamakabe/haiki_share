@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-mypage">
            <h1 class="c-title">{{ __('Convenience Store MyPage') }}</h1>
            <p style="font-weight:bold; font-size: 24px;">ユーザー名は、{{ Auth::user()->name }}です。</p>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <div class="p-mypage__button" style="display:flex; justify-content:center;">
                    <!-- プロフィール編集ボタンを表示する（ボタンクリックで画面遷移する） -->
                    <a href="{{ route('convenience.profile.show', ['userId' => $user->id]) }}" class="c-button">{{ __('Edit Profile') }}</a>

                    <!-- 退会画面へのリンクを追加 -->
                    <a href="{{ route('convenience.withdraw.show', ['userId' => $user->id]) }}" class="c-button" style="margin-left: 20px;">{{ __('Withdraw') }}</a>

                    <!-- 商品一覧画面へのリンクを追加 -->
                    <a href="{{ route('convenience.productIndex.show') }}" class="c-button" style="margin-left: 20px;">{{ __('ProductIndex') }}</a>

                    <!-- 商品出品画面へのリンクを追加 -->
                    <a href="{{ route('convenience.productSale.show', ['userId' => $user->id]) }}" class="c-button" style="margin-left: 20px;">{{ __('ProductSale') }}</a>
                </div>
        </div>
    </main>
</div>
@endsection

<!-- 商品出品ボタンを表示する（ボタンクリックで画面遷移する） -->
<!-- 出品した商品を一覧の最新5件を表示する（「全件表示」をクリックすると一覧画面に遷移する） -->
<!-- 購入された商品の最新5件をする（「全件表示」をクリックすると一覧画面に遷移する） -->