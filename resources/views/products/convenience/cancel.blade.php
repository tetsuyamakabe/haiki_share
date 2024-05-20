{{ $product->convenience->user->name }} 様<br>
{{ config('app.name') }}をご利用いただき、ありがとうございます。<br>
<br>
このメールは、{{ config('app.name') }}での商品購入キャンセルが完了したことをお知らせするために送信されています。<br>
<br>
=============================<br>
購入キャンセル商品情報<br>
=============================<br>
【商品名】{{ $product->name }}<br>
【価格】{{ $product->price }} 円<br>
【賞味期限】{{ $formattedExpirationDate }}<br>
<br>
=============================<br>
利用者情報<br>
=============================<br>
【利用者名】{{ Auth::user()->name }}<br>
<br>
商品の購入キャンセル処理が完了しました。<br>
<br>
またのご利用お待ちしております。