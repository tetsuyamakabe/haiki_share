@component('mail::message')
以下のボタンを押して、パスワードリセットの手続きを行ってください。

@component('mail::button', ['url' => $reset_url ])
パスワードリセット
@endcomponent

----

このリンクの有効期間は{{ $count }} 分です。

もしこのメールに心当たりがない場合は破棄してください。

@endcomponent