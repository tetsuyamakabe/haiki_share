@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-profile">
            <div class="p-profile__form">
                <h1 class="c-title">{{ __('Convenience Store Profile') }}</h1>
                <convenience-profile-component :user="{{ $user }}" :convenience="{{ $convenience }}" :address="{{ $address }}" :user-id="{{ $user->id }}"></convenience-profile-component>
            </div>
        </div>
    </main>
</div>
@endsection

<!-- コンビニ側のプロフィール編集画面 -->
<!-- コンビニ名、支店名、住所、メールアドレス、パスワードを編集可能にする -->