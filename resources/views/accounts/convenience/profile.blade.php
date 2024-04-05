@extends('layouts.common')

@section('content')
<div id="app">
    <main class="l-main">
        <div class="p-profile">
            <div class="p-profile__form">
                <h1 class="c-title">{{ __('Convenience Store Profile') }}</h1>
                <form method="POST" action="{{ route('convenience.profile.edit', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <convenience-profile-component :user="{{ $user }}" :convenience="{{ $convenience }}" :address="{{ $address }}" :user-id="{{ $user->id }}"></convenience-profile-component>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection

<!-- コンビニ側のプロフィール編集画面 -->
<!-- コンビニ名、支店名、住所、メールアドレス、パスワードを編集可能にする -->