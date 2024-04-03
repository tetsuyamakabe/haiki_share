@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-profile">
            <div class="p-profile__form">
                <h1 class="c-title">{{ __('User Profile') }}</h1>
                <form method="POST" action="{{ route('user.profile.edit', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <user-profile-component :user="{{ $user }}" :user-id="{{ $user->id }}"></user-profile-component>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection

<!-- 利用者側のプロフィール編集画面 -->
<!-- メールアドレス、パスワードを編集可能にする -->