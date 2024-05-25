<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Models\Like;
use App\Models\User;
use App\Models\Address;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use App\Notifications\User\CancelNotification;
use App\Notifications\User\PurchaseNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    // 正常系テスト
    public function test_商品購入処理()
    {
        // fakeメソッドの準備
        Notification::fake();
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // テスト用の商品を作成
        $product = factory(Product::class)->create();
        // テスト用のリクエストを送信
        $response = $this->actingAs($user)->json('POST', '/api/user/products/purchase/' . $product->id);
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // 購入モデルから購入商品を取得
        $purchase = Purchase::first();
        // 購入商品が存在するか
        $this->assertNotNull($purchase);
        // 購入商品情報が正しいか（商品ID、購入者ID、購入済みフラグ）
        $this->assertEquals($purchase['product_id'], $product->id);
        $this->assertEquals($purchase['purchased_id'], $user->id);
        $this->assertEquals(true, $purchase['is_purchased']);
        // 購入完了メールが購入者に送信されたか
        Notification::assertSentTo($user, PurchaseNotification::class);
        // 購入完了メールがコンビニユーザーに送信されたか
        Notification::assertSentTo($product->convenience->user, PurchaseNotification::class);
    }

    public function test_商品キャンセル処理()
    {
        // fakeメソッドの準備
        Notification::fake();
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // テスト用の商品を作成
        $product = factory(Product::class)->create();
        // テスト用の購入商品を作成
        $purchase = factory(Purchase::class)->create([
            'product_id' => $product->id,
            'purchased_id' => $user->id
        ]);
        // テスト用のリクエストを送信
        $response = $this->actingAs($user)->json('DELETE', '/api/user/products/purchase/cancel/' . $product->id);
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // 購入モデルから購入商品を取得
        $cancelPurchase = Purchase::where('product_id', $product->id)->where('purchased_id', $user->id)->first();
        // 商品の購入がキャンセルされたか
        $this->assertNull($cancelPurchase);
        // 商品のキャンセル通知が利用者に送信されたか
        Notification::assertSentTo($user, CancelNotification::class);
        // 商品のキャンセル通知がコンビニユーザーに送信されたか
        Notification::assertSentTo($product->convenience->user, CancelNotification::class);
    }

    public function test_商品お気に入り登録処理()
    {
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // テスト用の商品を作成
        $product = factory(Product::class)->create();
        // テスト用のリクエストを送信
        $response = $this->actingAs($user)->json('POST', '/api/user/like/' . $product->id);
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // レスポンスデータに必要な情報が含まれているか
        $this->assertArrayHasKey('message', $responseData);
        // レスポンスデータの内容が正しいか
        $this->assertEquals('商品をお気に入り登録しました', $responseData['message']);
    }

    public function test_商品お気に入り解除処理()
    {
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // テスト用の商品を作成
        $product = factory(Product::class)->create();
        // お気に入り登録した商品を作成
        $likedProduct = factory(Like::class)->create(['product_id' => $product->id, 'user_id' => $user->id]);
        // テスト用のリクエストを送信
        $response = $this->actingAs($user)->json('POST', '/api/user/unlike/' . $product->id);
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // レスポンスデータに必要な情報が含まれているか
        $this->assertArrayHasKey('message', $responseData);
        // レスポンスデータの内容が正しいか
        $this->assertEquals('商品のお気に入り登録を解除しました', $responseData['message']);
    }

    public function test_購入した商品情報の取得処理()
    {
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // テスト用の購入済み商品を作成
        $purchasedProducts = factory(Purchase::class, 5)->create(['purchased_id' => $user->id]);
        // リクエストを送信
        $response = $this->actingAs($user)->json('GET', '/api/user/products/purchased');
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        $this->assertArrayHasKey('products', $responseData);
        // ページネーションが含まれているか
        $this->assertArrayHasKey('current_page', $responseData['products']);
        $this->assertArrayHasKey('last_page', $responseData['products']);
        // レスポンスの商品数が正しいか
        $this->assertCount(5, $responseData['products']['data']);
        // レスポンスの商品の中身は正しいか
        foreach ($responseData['products']['data'] as $product) {
            $this->assertArrayHasKey('product', $product);
            $this->assertArrayHasKey('name', $product['product']);
            $this->assertArrayHasKey('price', $product['product']);
            $this->assertArrayHasKey('expiration_date', $product['product']);
            $this->assertArrayHasKey('category_id', $product['product']);
            $this->assertArrayHasKey('convenience_store_id', $product['product']);
            $this->assertArrayHasKey('pictures', $product['product']);
        }
    }

    public function test_お気に入り登録した商品情報の取得()
    {
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // テスト用のお気に入り商品を作成
        $likedProducts = factory(Product::class, 5)->create();
        foreach ($likedProducts as $product) {
            factory(Like::class)->create(['user_id' => $user->id, 'product_id' => $product->id]);
        }
        // リクエストを送信
        $response = $this->actingAs($user)->json('GET', '/api/user/products/liked');
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        $this->assertArrayHasKey('products', $responseData);
        // ページネーションが含まれているか
        $this->assertArrayHasKey('current_page', $responseData['products']);
        $this->assertArrayHasKey('last_page', $responseData['products']);
        // レスポンスの商品数が正しいか
        $this->assertCount(5, $responseData['products']['data']);
        // レスポンスの商品の中身は正しいか
        foreach ($responseData['products']['data'] as $product) {
            $this->assertArrayHasKey('product', $product);
            $this->assertArrayHasKey('name', $product['product']);
            $this->assertArrayHasKey('price', $product['product']);
            $this->assertArrayHasKey('expiration_date', $product['product']);
            $this->assertArrayHasKey('category_id', $product['product']);
            $this->assertArrayHasKey('convenience_store_id', $product['product']);
            $this->assertArrayHasKey('pictures', $product['product']);
        }
    }

    public function test_出品しているコンビニがある都道府県の取得()
    {
        // テスト用の住所を作成
        factory(Address::class)->create(['prefecture' => '東京都']);
        factory(Address::class)->create(['prefecture' => '大阪府']);
        factory(Address::class)->create(['prefecture' => '東京都']);
        factory(Address::class)->create(['prefecture' => '北海道']);
        factory(Address::class)->create(['prefecture' => '東京都']);
        // リクエストを送信
        $response = $this->get('/api/prefecture');
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        $this->assertArrayHasKey('prefectures', $responseData);
        // prefectures キーの値が配列であるか
        $this->assertIsArray($responseData['prefectures']);
        // 重複を削除しているため、ユニークな値のみが含まれるか
        $this->assertCount(3, $responseData['prefectures']);
    }

    // 異常系テスト
    public function test_未認証ユーザーの商品購入処理()
    {
        // テスト用の商品を作成
        $product = factory(Product::class)->create();
        // 未認証で不正なリクエストを送信
        $response = $this->json('POST', '/api/user/products/purchase/' . $product->id);
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_商品が見つからない商品購入処理()
    {
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // 不正な商品IDを使用してリクエストを送信
        $response = $this->actingAs($user)->json('POST', '/api/user/products/purchase/product_id');
        // エラーレスポンスが返されるか
        $response->assertStatus(404);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('商品が見つかりません。', $responseData['error']);
    }

    public function test_未認証ユーザーの商品購入キャンセル処理()
    {
        // テスト用の商品を作成
        $product = factory(Product::class)->create();
        // 未認証で不正なリクエストを送信
        $response = $this->json('DELETE', '/api/user/products/purchase/cancel/' . $product->id);
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_商品が見つからない商品キャンセル処理()
    {
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // 存在しない商品IDを使用してリクエストを送信
        $response = $this->actingAs($user)->json('DELETE', '/api/user/products/purchase/cancel/product_id');
        // エラーレスポンスが返されるか
        $response->assertStatus(404);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('商品が見つかりません。', $responseData['error']);
    }

    public function test_未認証ユーザーの商品お気に入り登録処理()
    {
        // テスト用の商品を作成
        $product = factory(Product::class)->create();
        // 未認証で不正なリクエストを送信
        $response = $this->json('POST', '/api/user/like/' . $product->id);
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_すでにお気に入り登録されている商品のお気に入り登録処理()
    {
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // テスト用の商品を作成
        $product = factory(Product::class)->create();
        // お気に入り登録した商品を作成
        $likedProduct = factory(Like::class)->create(['product_id' => $product->id, 'user_id' => $user->id]);
        // 認証済みの状態でリクエストを送信
        $response = $this->actingAs($user)->json('POST', '/api/user/like/' . $product->id);
        // エラーレスポンスが返されるか
        $response->assertStatus(400);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('すでにお気に入り登録されています', $responseData['message']);
    }

    public function test_未認証ユーザーの商品お気に入り解除処理()
    {
        // テスト用の商品を作成
        $product = factory(Product::class)->create();
        // 未認証で不正なリクエストを送信
        $response = $this->json('POST', '/api/user/unlike/' . $product->id);
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_すでにお気に入り登録されている商品のお気に入り解除処理()
    {
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create(['role' => 'user']);
        // テスト用の商品を作成
        $product = factory(Product::class)->create();
        // 認証済みの状態でリクエストを送信
        $response = $this->actingAs($user)->json('POST', '/api/user/unlike/' . $product->id);
        // エラーレスポンスが返されるか
        $response->assertStatus(400);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('お気に入り登録が見つかりません', $responseData['message']);
    }

    public function test_未認証ユーザーの購入した商品情報の取得処理()
    {
        // 未認証で不正なリクエストを送信
        $response = $this->json('GET', '/api/user/products/purchased');
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_未認証ユーザーのお気に入り登録した商品情報の取得()
    {
        // 未認証で不正なリクエストを送信
        $response = $this->json('GET', '/api/user/products/liked');
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }
}