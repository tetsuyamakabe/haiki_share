@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-mypage">
            <h1 class="c-title">{{ __('User MyPage') }}</h1>
            <p style="font-weight:bold; font-size: 24px;">ユーザー名は、{{ Auth::user()->name }}です。</p>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <div class="p-mypage__button" style="display:flex; justify-content:center;">
                    <!-- プロフィール編集ボタンを表示する（ボタンクリックで画面遷移する） -->
                    <a href="{{ route('user.profile.show', ['userId' => $user->id]) }}" class="c-button">{{ __('Edit Profile') }}</a>
                    
                    <!-- 退会画面へのリンクを追加 -->
                    <a href="{{ route('user.withdraw.show', ['userId' => $user->id]) }}" class="c-button" style="margin-left: 20px;">{{ __('Withdraw') }}</a>

                    <!-- 商品一覧画面へのリンクを追加 -->
                    <a href="{{ route('product.productAllIndex.show') }}" class="c-button" style="margin-left: 20px;">{{ __('Product All Index') }}</a>
                </div>
        </div>
    </main>
</div>
@endsection
<!-- 購入した商品を一覧表示する（各商品ごとに「詳細を見る」「購入をキャンセルする」ボタンがある） -->