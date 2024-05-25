<?php

namespace Tests\Unit\Convenience;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Convenience;
use App\Models\ProductPicture;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyPageTest extends TestCase
{
    use RefreshDatabase;

    // 正常系テスト
    public function test_コンビニ側プロフィール情報の取得処理()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
        // テスト用コンビニ情報と関連付ける住所情報を作成
        $convenience = factory(Convenience::class)->create(['user_id' => $user->id]);
        $address = $convenience->address;
        // テスト用のリクエストを送信
        $response = $this->actingAs($user)->json('GET', '/api/convenience/mypage/profile');
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // 正しいユーザー情報が含まれているか
        $this->assertArrayHasKey('user', $responseData);
        $this->assertEquals($user->id, $responseData['user']['id']);
        $this->assertEquals($user->name, $responseData['user']['name']);
        $this->assertEquals($user->email, $responseData['user']['email']);
        $this->assertEquals($user->icon, $responseData['user']['icon']);
        $this->assertEquals($user->introduction, $responseData['user']['introduction']);
        // 正しいコンビニ情報が含まれているか
        $this->assertArrayHasKey('convenience', $responseData);
        $this->assertEquals($convenience->id, $responseData['convenience']['id']);
        $this->assertEquals($convenience->branch_name, $responseData['convenience']['branch_name']);
        // 正しい住所情報が含まれているか
        $this->assertArrayHasKey('address', $responseData);
        $this->assertEquals($address->id, $responseData['address']['id']);
        $this->assertEquals($address->prefecture, $responseData['address']['prefecture']);
        $this->assertEquals($address->city, $responseData['address']['city']);
        $this->assertEquals($address->town, $responseData['address']['town']);
        $this->assertEquals($address->building, $responseData['address']['building']);
    }

    public function test_コンビニ側プロフィール編集処理()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
        // テスト用コンビニ情報と関連付ける住所情報を作成
        $convenience = factory(Convenience::class)->create(['user_id' => $user->id]);
        $address = $convenience->address;
        // 画像fakeメソッドの準備
        Storage::fake('icon');
        // テストデータの作成
        $data = [
            'convenience_name' => 'セブンイレブン', // コンビニ名
            'email' => 'sample@example.com', // メールアドレス
            'password' => 'newpassword', // 新しいパスワード
            'password_confirmation' => 'newpassword', // パスワード（再入力）
            'introduction' => 'よろしくお願いします', // 自己紹介文
            'prefecture' => '東京都', // 住所（都道府県）
            'city' => '新宿区', // 住所（市区町村）
            'town' => '新宿', // 住所（地名・番地）
            'building' => '1-2-26', // 住所（建物名・部屋番号）
            'branch_name' => '新宿一丁目店', // 支店名
        ];
        // テスト画像ファイル
        $file = [
            'icon' => UploadedFile::fake()->image('icon.jpg', 100, 100)->size(2000), // 2MB以内の顔写真
        ];
        // テスト用のリクエストを作成（リクエストヘッダーを含める）
        $response = $this->actingAs($user)
            ->withHeaders([
                'Content-Type' => 'multipart/form-data',
            ])->json('PUT', '/api/convenience/mypage/profile', $data, $file);
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // レスポンスデータにコンビニ情報を追加
        $responseData['convenience'] = [
            'branch_name' => $convenience->branch_name,
        ];
        // レスポンスデータに住所情報を追加
        $responseData['address'] = [
            'prefecture' => $address->prefecture,
            'city' => $address->city,
            'town' => $address->town,
            'building' => $address->building,
        ];
        // ユーザー情報が更新されているか
        $this->assertEquals($user->name, $responseData['user']['name']);
        $this->assertEquals($user->email, $responseData['user']['email']);
        $this->assertEquals($user->introduction, $responseData['user']['introduction']);
        // コンビニ情報が更新されているか
        $this->assertEquals($convenience->branch_name, $responseData['convenience']['branch_name']);
        // 住所情報が更新されているか
        $this->assertEquals($address->prefecture, $responseData['address']['prefecture']);
        $this->assertEquals($address->city, $responseData['address']['city']);
        $this->assertEquals($address->town, $responseData['address']['town']);
        $this->assertEquals($address->building, $responseData['address']['building']);
        // パスワードが更新されているか
        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
        // 顔写真がアップロードされているか
        Storage::disk('public')->assertExists('icons/' . $responseData['user']['icon']);
    }

    public function test_コンビニ側退会処理()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
        // ユーザーに関連づけられたコンビニ情報と住所情報を作成
        $convenience = factory(Convenience::class)->create(['user_id' => $user->id]);
        $address = $convenience->address;
        // テスト用のリクエストを送信
        $response = $this->actingAs($user)->json('DELETE', '/api/convenience/mypage/withdraw');
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // ログアウトしているか
        $this->assertGuest();
        // ユーザーが論理削除されているか
        $this->assertSoftDeleted('users', ['id' => $user->id]);
        // コンビニ情報が論理削除されているか
        $this->assertSoftDeleted('convenience_stores', ['id' => $convenience->id]);
        // 住所情報が論理削除されているか
        $this->assertSoftDeleted('addresses', ['id' => $address->id]);
        // レスポンスデータに必要な情報が含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('user', $responseData);
        // レスポンスデータの内容が正しいか
        $this->assertEquals('ユーザーが退会しました', $responseData['message']);
        $this->assertEquals($user->toArray(), $responseData['user']);
    }

    public function test_コンビニ側マイページ商品取得()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
        // 関連付けられたコンビニ情報を作成
        $convenience = factory(Convenience::class)->create(['user_id' => $user->id]);
        // 商品情報と関連付けの商品画像を作成
        $products = factory(Product::class, 5)->create();
        $products->each(function ($product) {
            $product->update(['name' => 'ロースとんかつ弁当']);
            factory(ProductPicture::class, 5)->create(['product_id' => $product->id]);
        });
        // 出品した商品を作成
        $saleProducts = factory(Product::class, 5)->create(['convenience_store_id' => $convenience->id]);
        // // 購入された商品を作成
        $purchasedProducts = factory(Product::class, 5)->create(['convenience_store_id' => $convenience->id]);
        $purchasedProducts->each(function ($product) {
            // 購入状態モデルのインスタンスを生成し、is_purchasedをtrueに設定
            $purchase = factory(Purchase::class)->create(['is_purchased' => true]);
            // 商品モデルと購入状態モデルを関連付ける
            $product->purchases()->save($purchase);
        });
        // テスト用のリクエストを送信
        $response = $this->actingAs($user)->json('GET', '/api/convenience/mypage/products');
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // 出品した商品が正しく5件含まれているか
        $this->assertArrayHasKey('sale_products', $responseData);
        $this->assertCount(5, $responseData['sale_products']);
        // 出品した商品の中身は正しいか
        foreach ($responseData['sale_products'] as $product) {
            $this->assertArrayHasKey('name', $product);
            $this->assertArrayHasKey('price', $product);
            $this->assertArrayHasKey('expiration_date', $product);
            $this->assertArrayHasKey('category_id', $product);
            $this->assertArrayHasKey('convenience_store_id', $product);
            $this->assertArrayHasKey('pictures', $product);
        }
        // 購入された商品が正しく5件含まれているか
        $this->assertArrayHasKey('purchased_products', $responseData);
        $this->assertCount(5, $responseData['purchased_products']);
        // 購入された商品の中身は正しいか
        foreach ($responseData['purchased_products'] as $product) {
            $this->assertArrayHasKey('name', $product);
            $this->assertArrayHasKey('price', $product);
            $this->assertArrayHasKey('expiration_date', $product);
            $this->assertArrayHasKey('category_id', $product);
            $this->assertArrayHasKey('convenience_store_id', $product);
            $this->assertArrayHasKey('pictures', $product);
        }
    }

    // 異常系テスト
    public function test_コンビニ側未認証ユーザーのプロフィール情報の取得処理()
    {
        // 未認証で不正なリクエストを送信
        $response = $this->json('GET', '/api/convenience/mypage/profile');
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_コンビニ側未認証ユーザーのプロフィール情報の編集処理()
    {
        // 未認証で不正なリクエストを送信
        $response = $this->withHeaders([
                'Content-Type' => 'multipart/form-data',
            ])->json('PUT', '/api/convenience/mypage/profile');
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_コンビニ側プロフィール編集バリデーションチェック()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
        // テスト用コンビニ情報と関連付ける住所情報を作成
        $convenience = factory(Convenience::class)->create(['user_id' => $user->id]);
        $address = $convenience->address;
        // テストデータの作成
        $data = [
            'convenience_name' => '', // 必須項目なので空にする
            'email' => 'aaa', // 不正な形式のメールアドレス
            'password' => 'pass', // パスワードが短すぎる
            'password_confirmation' => 'password', // パスワード確認が一致しない
            'introduction' => str_repeat('あ', 51), // 自己紹介文の文字数オーバー
            'icon' => UploadedFile::fake()->image('sample_image.pdf')->size(3000), // 拡張子がpdfで画像サイズが3MB
            'prefecture' => '', // 必須項目なので空にする
            'city' => '', // 必須項目なので空にする
            'town' => '',// 必須項目なので空にする
            'branch_name' => '', // 必須項目なので空にする
        ];
        // リクエストを送信
        $response = $this->actingAs($user)
            ->withHeaders([
                'Content-Type' => 'multipart/form-data',
            ])->json('PUT', '/api/convenience/mypage/profile', $data);
        // エラーレスポンスが返されるか
        $response->assertStatus(422);
        // エラーメッセージが正しいか
        $response->assertJsonValidationErrors(['convenience_name', 'email', 'password', 'introduction', 'icon', 'prefecture', 'city', 'town', 'branch_name']);
    }

    public function test_コンビニ側未認証ユーザーの退会処理()
    {
        // 不正なリクエストを送信
        $response = $this->json('DELETE', '/api/convenience/mypage/withdraw');
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_コンビニ側未認証ユーザーのマイページ商品取得()
    {
        // 未認証で不正なリクエストを送信
        $response = $this->json('GET', '/api/convenience/mypage/products');
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }
}