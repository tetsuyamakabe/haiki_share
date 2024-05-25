<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Models\Like;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\ProductPicture;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use App\Notifications\User\ContactNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyPageTest extends TestCase
{
    use RefreshDatabase;

    // 正常系テスト
    public function test_利用者側プロフィール情報の取得処理()
    {
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // テスト用のリクエストを送信
        $response = $this->actingAs($user)->json('GET', '/api/user/mypage/profile');
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
    }

    public function test_利用者側プロフィール編集処理()
    {
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // 画像fakeメソッドの準備
        Storage::fake('icon');
        // テストデータの作成
        $data = [
            'name' => 'サンプルユーザー', // 名前
            'email' => 'sample@example.com', // メールアドレス
            'password' => 'newpassword', // 新しいパスワード
            'password_confirmation' => 'newpassword', // パスワード（再入力）
            'introduction' => 'よろしくお願いします', // 自己紹介文
        ];
        // テスト画像ファイル
        $file = [
            'icon' => UploadedFile::fake()->image('icon.jpg', 100, 100)->size(2000), // 2MB以内の顔写真
        ];
        // テスト用のリクエストを作成（リクエストヘッダーを含める）
        $response = $this->actingAs($user)
            ->withHeaders([
                'Content-Type' => 'multipart/form-data',
            ])->json('PUT', '/api/user/mypage/profile', $data, $file);
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // ユーザー情報が更新されているか
        $this->assertEquals($user->name, $responseData['user']['name']);
        $this->assertEquals($user->email, $responseData['user']['email']);
        $this->assertEquals($user->introduction, $responseData['user']['introduction']);
        // パスワードが更新されているか
        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
        // 顔写真がアップロードされているか
        Storage::disk('public')->assertExists('icons/' . $responseData['user']['icon']);
    }

    public function test_利用者側退会処理()
    {
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // テスト用のリクエストを送信
        $response = $this->actingAs($user)->json('DELETE', '/api/user/mypage/withdraw');
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // ログアウトしているか
        $this->assertGuest();
        // ユーザーが論理削除されているか
        $this->assertSoftDeleted('users', ['id' => $user->id]);
        // レスポンスデータに必要な情報が含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('user', $responseData);
        // レスポンスデータの内容が正しいか
        $this->assertEquals('ユーザーが退会しました', $responseData['message']);
        $this->assertEquals($user->toArray(), $responseData['user']);
    }

    public function test_利用者側マイページ商品取得()
    {
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // 商品情報と関連付けの商品画像を作成
        $products = factory(Product::class, 5)->create();
        $products->each(function ($product) {
            factory(ProductPicture::class, 5)->create(['product_id' => $product->id]);
        });
        // 購入済み商品を作成
        factory(Purchase::class, 5)->create(['purchased_id' => $user->id]);
        // お気に入り商品を作成
        factory(Like::class, 5)->create(['user_id' => $user->id]);
        // テスト用のリクエストを送信
        $response = $this->actingAs($user)->json('GET', '/api/user/mypage/products');
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // 購入済み商品が正しく5件含まれているか
        $this->assertArrayHasKey('purchased_products', $responseData);
        $this->assertCount(5, $responseData['purchased_products']);
        // 購入済み商品の中身は正しいか
        foreach ($responseData['purchased_products'] as $product) {
            $this->assertArrayHasKey('product', $product);
            $this->assertArrayHasKey('name', $product['product']);
            $this->assertArrayHasKey('price', $product['product']);
            $this->assertArrayHasKey('expiration_date', $product['product']);
            $this->assertArrayHasKey('category_id', $product['product']);
            $this->assertArrayHasKey('convenience_store_id', $product['product']);
            $this->assertArrayHasKey('pictures', $product['product']);
        }
        // お気に入り商品が正しく5件含まれているか
        $this->assertArrayHasKey('liked_products', $responseData);
        $this->assertCount(5, $responseData['liked_products']);
        // お気に入り商品の中身は正しいか
        foreach ($responseData['liked_products'] as $product) {
            $this->assertArrayHasKey('product', $product);
            $this->assertArrayHasKey('name', $product['product']);
            $this->assertArrayHasKey('price', $product['product']);
            $this->assertArrayHasKey('expiration_date', $product['product']);
            $this->assertArrayHasKey('category_id', $product['product']);
            $this->assertArrayHasKey('convenience_store_id', $product['product']);
            $this->assertArrayHasKey('pictures', $product['product']);
        }
    }

    public function test_お問い合わせ処理()
    {
        // fakeメソッドの準備
        Notification::fake();
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // テストデータの作成
        $data = [
            'name' => 'テストユーザー',
            'email' => 'sample@example.com',
            'contact' => 'お問い合わせ内容'
        ];
        // テスト用のリクエストを送信
        $response = $this->json('POST', '/api/contact', $data);
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // メールが送信されたか
        Notification::assertTimesSent(1, ContactNotification::class);
        // レスポンスデータを取得
        $responseData = $response->json();
        // レスポンスデータに必要な情報が含まれているか
        $this->assertArrayHasKey('message', $responseData);
        // レスポンスデータの内容が正しいか
        $this->assertEquals('お問い合わせが送信されました', $responseData['message']);
    }

    // 異常系テスト
    public function test_利用者側未認証ユーザーのプロフィール情報の取得処理()
    {
        // 未認証で不正なリクエストを送信
        $response = $this->json('GET', '/api/user/mypage/profile');
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_利用者側未認証ユーザーのプロフィール情報の編集処理()
    {
        // 未認証で不正なリクエストを送信
        $response = $this->withHeaders([
                'Content-Type' => 'multipart/form-data',
            ])->json('PUT', '/api/user/mypage/profile');
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_利用者側プロフィール編集バリデーションチェック()
    {
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // テストデータの作成
        $data = [
            'name' => '', // 必須項目なので空にする
            'email' => 'aaa', // 不正な形式のメールアドレス
            'password' => 'pass', // パスワードが短すぎる
            'password_confirmation' => 'password', // パスワード確認が一致しない
            'introduction' => str_repeat('あ', 51), // 自己紹介文の文字数オーバー
            'icon' => UploadedFile::fake()->image('sample_image.pdf')->size(3000), // 拡張子がpdfで画像サイズが3MB
        ];
        // リクエストを送信
        $response = $this->actingAs($user)
            ->withHeaders([
                'Content-Type' => 'multipart/form-data',
            ])->json('PUT', '/api/user/mypage/profile', $data);
        // エラーレスポンスが返されるか
        $response->assertStatus(422);
        // エラーメッセージが正しいか
        $response->assertJsonValidationErrors(['name', 'email', 'password', 'introduction', 'icon']);
    }

    public function test_利用者側未認証ユーザーの退会処理()
    {
        // 不正なリクエストを送信
        $response = $this->json('DELETE', '/api/user/mypage/withdraw');
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_利用者側未認証ユーザーのマイページ商品取得()
    {
        // 未認証で不正なリクエストを送信
        $response = $this->json('GET', '/api/user/mypage/products');
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_お問い合わせバリデーションチェック()
    {
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // テストデータの作成
        $data = [
            'name' => '', // 必須項目なので空にする
            'email' => 'aaa', // 不正な形式のメールアドレス
            'contact' => '', // 必須項目なので空にする
        ];
        // リクエストを送信
        $response = $this->actingAs($user)->json('POST', '/api/contact', $data);
        // エラーレスポンスが返されるか
        $response->assertStatus(422);
        // エラーメッセージが正しいか
        $response->assertJsonValidationErrors(['name', 'email', 'contact']);
    }
}