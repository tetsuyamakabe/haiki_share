@component('mail::message')
ボタンを押して、パスワードリセットの手続きを行ってください。

@component('mail::button', ['url' => $reset_url ])
パスワードリセット
@endcomponent

----
もしこのメールに心当たりがない場合は破棄してください。

@endcomponent