<?php

namespace Tests\Unit\Convenience;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Convenience;
use App\Models\ProductPicture;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    // 正常系テスト
    public function test_商品出品処理()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create([
            'name' => 'ローソン',
            'email' => 'convenience@example.com',
            'password' => bcrypt('password'),
            'role' => 'convenience',
            'icon' => 'default.png',
            'introduction' => ''
        ]);
        // テスト用のコンビニ情報の作成
        $convenience = factory(Convenience::class)->create(['user_id' => $user->id]);
        // 商品カテゴリの作成
        $category = factory(Category::class)->create();

        // 画像fakeメソッドの準備
        Storage::fake('product_picture');

        // テストデータの作成
        $data = [
            'name' => '手巻おにぎり　ツナマヨネーズ',
            'price' => '130',
            'expiration_date' => '2024-05-20',
            'category_id' => $category->id,
            'convenience_id' => $convenience->id
        ];
        // テスト画像ファイル
        $file = [
            'product_picture' => UploadedFile::fake()->image('product.jpg', 100, 100)->size(2000), // 2MB以内の商品画像
        ];

        // テスト用のリクエストを作成
        $response = $this->actingAs($user)
            ->withHeaders([
                'Content-Type' => 'multipart/form-data',
            ])->json('PUT', '/api/convenience/products/sale', $data, $file);

        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // 作成された商品を取得
        $product = Product::find($responseData['product']['id']);
        // 商品情報が出品されているか
        $this->assertEquals($data['name'], $product->name);
        $this->assertEquals($data['price'], $product->price);
        $this->assertEquals($data['category_id'], $product->category_id);
        $this->assertEquals($data['expiration_date'], $product->expiration_date);
        // 商品画像がアップロードされているか
        Storage::disk('public')->assertExists('product_pictures/' . $product->productPicture->file);
    }

    public function test_商品編集処理()
    {
        // テスト用のユーザーを作成
        $user = User::create([
            'name' => 'ローソン',
            'email' => 'convenience@example.com',
            'password' => bcrypt('password'),
            'role' => 'convenience',
            'icon' => 'default.png',
            'introduction' => ''
        ]);
        // テスト用のコンビニ情報の作成
        $convenience = factory(Convenience::class)->create(['user_id' => $user->id]);
        // テスト用のカテゴリ情報の作成
        $category = factory(Category::class)->create();
        // テスト用の商品を作成
        $product = Product::create([
            'name' => '手巻おにぎり　ツナマヨネーズ',
            'price' => '130',
            'expiration_date' => '2024-05-20',
            'category_id' => $category->id,
            'convenience_store_id' => $user->convenience->id
        ]);
        // dd($product);

        // 画像fakeメソッドの準備
        Storage::fake('product_picture');

        // テスト用のリクエストデータを作成
        $data = [
            'name' => '味付海苔　炭火焼熟成紅しゃけ',
            'price' => '170',
            'expiration_date' => '2024-05-24',
            'category_id' => $category->id,
            'convenience_store_id' => $user->convenience->id
        ];
        // dd($data);
        $file = [
            'product_picture' => UploadedFile::fake()->image('product.jpg', 100, 100)->size(2000), // 2MB以内の商品画像
        ];

        // テスト用のリクエストを作成
        $response = $this->actingAs($user)
            ->withHeaders([
                'Content-Type' => 'multipart/form-data',
            ])->json('PUT', '/api/convenience/products/edit/' . $product->id, $data, ['product_picture' => $file]);
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // レスポンスデータが期待通りのものであることを検証
        $this->assertEquals('商品の編集に成功しました', $responseData['message']);
        $this->assertEquals($data['name'], $responseData['product']['name']);
        $this->assertEquals($data['price'], $responseData['product']['price']);
        $this->assertEquals($data['category_id'], $responseData['product']['category_id']);
        $this->assertEquals($data['expiration_date'], $responseData['product']['expiration_date']);

        // 商品画像がアップロードされているか
        Storage::disk('public')->assertExists('product_pictures/' . $responseData['product']['productPicture']['file']);
    }

    // 異常系テスト
    public function test_未認証ユーザーの商品出品処理()
    {
        // 未認証で不正なリクエストを送信
        $response = $this->withHeaders([
                'Content-Type' => 'multipart/form-data',
            ])->json('PUT', '/api/convenience/products/sale');

        // エラーレスポンスが返されるか
        $response->assertStatus(401);

        // レスポンスデータを取得
        $responseData = $response->json();

        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_未認証ユーザーの商品編集処理()
    {
        // // テスト用の商品を作成
        $product = factory(Product::class)->create();
        // 未認証で不正なリクエストを送信
        $response = $this->withHeaders([
                'Content-Type' => 'multipart/form-data',
            ])->json('PUT', '/api/convenience/products/edit/' . $product->id);
        // エラーレスポンスが返されるか
        $response->assertStatus(401);

        // レスポンスデータを取得
        $responseData = $response->json();

        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }
}