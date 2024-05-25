<?php

namespace Tests\Unit\Convenience;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
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
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
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
            'category' => $category->id,
            'convenience_id' => $convenience->id,
        ];
        // // テスト画像ファイル
        $file = [
            'product_picture' => UploadedFile::fake()->image('product.jpg', 100, 100)->size(2000), // 2MB以内の商品画像
        ];
        // テスト用のリクエストを作成
        $response = $this->actingAs($user)->withHeaders([
                'Content-Type' => 'multipart/form-data',
            ])->json('PUT', '/api/convenience/products/sale', $data, ['product_picture' => $file]);
        // dd($response->getContent());
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // dd($responseData);
        // 作成された商品を取得
        $product = Product::find($responseData['product']['id']);
        // 商品情報が出品されているか
        $this->assertEquals($data['name'], $product->name);
        $this->assertEquals($data['price'], $product->price);
        $this->assertEquals($data['category'], $product->category_id);
        $this->assertEquals($data['expiration_date'], $product->expiration_date);
        // 商品画像がアップロードされているか
        Storage::disk('public')->assertExists('product_pictures/' . $product->productPicture);
    }

    public function test_商品編集処理()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
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
            'convenience_store_id' => $user->convenience->id,
        ]);
        // 画像fakeメソッドの準備
        Storage::fake('product_picture');
        // テスト用のリクエストデータを作成
        $data = [
            'name' => '味付海苔　炭火焼熟成紅しゃけ',
            'price' => '170',
            'expiration_date' => '2024-05-23',
            'category' => $category->id,
            'convenience_store_id' => $user->convenience->id,
        ];
        $file = [
            'product_picture' => UploadedFile::fake()->image('product.jpg', 100, 100)->size(2000), // 2MB以内の商品画像
        ];
        // テスト用のリクエストを作成
        $response = $this->actingAs($user)->withHeaders([
                'Content-Type' => 'multipart/form-data',
            ])->json('PUT', '/api/convenience/products/edit/' . $product->id, $data, ['product_picture' => $file]);
        // dd($response->getContent());
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // dd($responseData);
        // レスポンスデータが期待通りのものであることを検証
        $this->assertEquals('商品の編集に成功しました', $responseData['message']);
        $this->assertEquals($data['name'], $responseData['product']['name']);
        $this->assertEquals($data['price'], $responseData['product']['price']);
        $this->assertEquals($data['category'], $responseData['product']['category_id']);
        // expiration_date の形式を合わせて比較（タイムゾーンを UTC に統一）
        $this->assertEquals(
            Carbon::parse($data['expiration_date'])->setTimezone('UTC')->toDateString(),
            Carbon::parse($responseData['product']['expiration_date'])->setTimezone('UTC')->toDateString()
        );
        // 商品画像がアップロードされているか
        Storage::disk('public')->assertExists('product_pictures/' . $product->productPicture);
    }

    public function test_商品削除処理()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
        // テスト用の商品を作成
        $product = factory(Product::class)->create();
        // テスト用の商品画像を作成
        $productPicture = factory(ProductPicture::class)->create(['product_id' => $product->id]);
        // テスト用のリクエストを送信
        $response = $this->actingAs($user)->json('DELETE', '/api/convenience/products/' . $product->id);
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // 商品情報が倫理削除されているか
        $this->assertSoftDeleted($product);
        // 商品画像が倫理削除されているか
        $this->assertSoftDeleted($productPicture);
        // レスポンスデータに必要な情報が含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('product', $responseData);
        // レスポンスデータの内容が正しいか
        $this->assertEquals('商品削除が完了しました', $responseData['message']);
        $this->assertArraySubset($product->toArray(), $responseData['product']); // deleted_atを除く
    }

    public function test_出品した商品情報の取得()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
        // テスト用のコンビニ情報の作成とユーザー情報との関連付け
        $convenience = factory(Convenience::class)->create(['user_id' => $user->id]);
        // テスト用の商品情報の作成とコンビニ情報との関連付け
        $products = factory(Product::class, 10)->create(['convenience_store_id' => $convenience->id]);
        // テスト用のリクエストを送信
        $response = $this->actingAs($user)->json('GET', '/api/convenience/products');
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        $this->assertArrayHasKey('products', $responseData);
        // ページネーションが含まれているか
        $this->assertArrayHasKey('current_page', $responseData['products']);
        $this->assertArrayHasKey('last_page', $responseData['products']);
        // レスポンスの商品数が正しいか
        $this->assertCount(10, $responseData['products']['data']);
        // レスポンスの商品の中身は正しいか
        foreach ($responseData['products']['data'] as $product) {
            $this->assertArrayHasKey('name', $product);
            $this->assertArrayHasKey('price', $product);
            $this->assertArrayHasKey('expiration_date', $product);
            $this->assertArrayHasKey('category_id', $product);
            $this->assertArrayHasKey('convenience_store_id', $product);
            $this->assertArrayHasKey('pictures', $product);
        }
    }

    public function test_購入された商品情報の取得()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
        // テスト用のコンビニの作成とユーザーとの関連付け
        $convenience = factory(Convenience::class)->create(['user_id' => $user->id]);
        // テスト用の商品の作成とコンビニとの関連付け
        $product = factory(Product::class)->create(['convenience_store_id' => $convenience->id]);
        // テスト用の購入情報を作成
        $purchasedProducts = factory(Purchase::class)->create(['product_id' => $product->id, 'is_purchased' => true]);
        // テスト用のリクエストを送信
        $response = $this->actingAs($user)->json('GET', '/api/convenience/products/purchased');
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // レスポンスデータに商品が含まれているか
        $this->assertArrayHasKey('products', $responseData);
        // ページネーションが含まれているか
        $this->assertArrayHasKey('current_page', $responseData['products']);
        $this->assertArrayHasKey('last_page', $responseData['products']);
        // レスポンスの商品数が正しいか
        $this->assertCount(1, $responseData['products']['data']);
        // レスポンスの商品の中身は正しいか
        foreach ($responseData['products']['data'] as $product) {
            $this->assertArrayHasKey('id', $product);
            $this->assertArrayHasKey('name', $product);
            $this->assertArrayHasKey('price', $product);
            $this->assertArrayHasKey('expiration_date', $product);
            $this->assertArrayHasKey('category_id', $product);
            $this->assertArrayHasKey('convenience_store_id', $product);
            $this->assertArrayHasKey('pictures', $product);
        }
    }

    public function test_すべての商品情報の取得()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
        // 東京都に関連する住所を作成
        $address = factory(Address::class)->create(['prefecture' => '東京都']);
        // テスト用のコンビニの作成とユーザー・住所との関連付け
        $convenience = factory(Convenience::class)->create(['user_id' => $user->id, 'address_id' => $address->id]);
        // テスト用の商品の作成とコンビニとの関連付け
        $products = factory(Product::class, 5)->create([
            'price' => 100,
            'expiration_date' => '2024-05-20',
            'convenience_store_id' => $convenience->id
        ]);
        // テスト用の検索条件を作成
        $data = [
            'prefecture' => '東京都',
            'minprice' => 100,
            'maxprice' => 500,
            'expiration_date' => 'true', // 賞味期限切れの商品
        ];
        // テスト用のリクエストを送信
        $response = $this->actingAs($user)->json('GET', '/api/products', $data);
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // レスポンスデータに商品が含まれているか
        $this->assertArrayHasKey('products', $responseData);
        // ページネーションが含まれているか
        $this->assertArrayHasKey('current_page', $responseData['products']);
        $this->assertArrayHasKey('last_page', $responseData['products']);
        // レスポンスの商品数が正しいか
        $this->assertCount(5, $responseData['products']['data']);
        // レスポンスの商品の中身（お気に入り情報・購入情報含む）は正しいか
        foreach ($responseData['products']['data'] as $product) {
            $this->assertArrayHasKey('id', $product);
            $this->assertArrayHasKey('name', $product);
            $this->assertArrayHasKey('price', $product);
            $this->assertArrayHasKey('expiration_date', $product);
            $this->assertArrayHasKey('category_id', $product);
            $this->assertArrayHasKey('convenience_store_id', $product);
            $this->assertArrayHasKey('pictures', $product);
            $this->assertArrayHasKey('liked', $product);
            $this->assertArrayHasKey('is_purchased', $product);
        }
    }

    public function test_商品情報の取得処理()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
        // テスト用の商品を作成
        $product = factory(Product::class)->create();
        // テスト用の購入情報を作成
        $purchase = factory(Purchase::class)->create(['product_id' => $product->id]);
        // テスト用のリクエストを送信
        $response = $this->actingAs($user)->json('GET', '/api/products/' . $product->id);
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // 商品情報に購入情報が含まれているかどうか
        $this->assertArrayHasKey('is_purchased', $responseData['product']);
        // 購入者のIDが正しいかどうか
        $this->assertEquals($purchase->purchaser->id, $responseData['product']['purchased_id']);
        // 商品がお気に入り登録されているかどうか
        $this->assertArrayHasKey('liked', $responseData['product']);
    }

    public function test_商品カテゴリ情報の取得処理()
    {
        // テスト用のカテゴリ5件を作成
        $categories = factory(Category::class, 5)->create();
        // テスト用のリクエストを送信
        $response = $this->getJson('/api/categories');
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // レスポンスデータにcategoriesが含まれているか
        $this->assertIsArray($responseData['categories']);
        // レスポンスのカテゴリ数は正しいか
        $this->assertCount(5, $responseData['categories']);
        // 各カテゴリの中身は正しいか
        foreach ($responseData['categories'] as $category) {
            $this->assertArrayHasKey('id', $category);
            $this->assertArrayHasKey('name', $category);
            $this->assertArrayHasKey('created_at', $category);
            $this->assertArrayHasKey('updated_at', $category);
        }
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
        // テスト用の商品を作成
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

    public function test_未認証ユーザーの商品削除処理()
    {
        // 未認証で不正なリクエストを送信
        $response = $this->json('DELETE', '/api/convenience/products/product_id');
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_商品が見つからない商品削除処理()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
        // 不正な商品IDを使用してリクエストを送信
        $response = $this->actingAs($user)->json('DELETE', '/api/convenience/products/product_id');
        // エラーレスポンスが返されるか
        $response->assertStatus(404);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('商品が見つかりません', $responseData['error']);
    }

    public function test_未認証ユーザーの出品した商品情報の取得処理()
    {
        // 未認証で不正なリクエストを送信
        $response = $this->json('GET', '/api/convenience/products');
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_コンビニが見つからない出品した商品情報の取得処理()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
        // 不正なリクエストを送信
        $response = $this->actingAs($user)->json('GET', '/api/convenience/products');
        // エラーレスポンスが返されるか
        $response->assertStatus(404);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('コンビニが見つかりません', $responseData['error']);
    }

    public function test_未認証ユーザーの購入された商品情報の取得処理()
    {
        // 未認証で不正なリクエストを送信
        $response = $this->json('GET', '/api/convenience/products/purchased');
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_コンビニが見つからない購入された商品情報の取得処理()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
        // 不正なリクエストを送信
        $response = $this->actingAs($user)->json('GET', '/api/convenience/products/purchased');
        // エラーレスポンスが返されるか
        $response->assertStatus(404);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('コンビニが見つかりません', $responseData['error']);
    }

    public function test_未認証ユーザーのすべての商品情報の取得処理()
    {
        // 未認証で不正なリクエストを送信
        $response = $this->json('GET', '/api/products');
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_未認証ユーザーの商品情報の取得処理()
    {
        // テスト用の商品を作成
        $product = factory(Product::class)->create();
        // 未認証で不正なリクエストを送信
        $response = $this->json('GET', '/api/products/' . $product->id);
        // エラーレスポンスが返されるか
        $response->assertStatus(401);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Unauthenticated.', $responseData['message']);
    }

    public function test_商品が見つからない商品情報の取得処理()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
        // 不正な商品IDを使用してリクエストを送信
        $response = $this->actingAs($user)->json('GET', '/api/products/product_id');
        // エラーレスポンスが返されるか
        $response->assertStatus(404);
        // レスポンスデータを取得
        $responseData = $response->json();
        // エラーメッセージが含まれているか
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('商品が見つかりません', $responseData['error']);
    }
}