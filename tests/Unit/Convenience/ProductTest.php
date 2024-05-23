<?php

namespace Tests\Unit\Convenience;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
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
        // 商品画像を準備
        Storage::fake('public');
        // 商品カテゴリの作成
        $category = factory(Category::class)->create();
        // テストデータの作成
        $data = [
            'name' => '手巻おにぎり　ツナマヨネーズ',
            'price' => '130',
            'expiration_date' => '2024-05-20',
            'category_id' => $category->id
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
        $this->assertNotNull($product->product_picture);

        // 商品画像がアップロードされているか
        Storage::disk('public')->assertExists('products/' . $product->product_picture);
    }
}