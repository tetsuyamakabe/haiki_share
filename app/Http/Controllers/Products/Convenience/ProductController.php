<?php

namespace App\Http\Controllers\Products\Convenience;

use Carbon\Carbon;
use App\Models\Like;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\ProductPicture;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Convenience\SearchRequest;
use App\Http\Requests\Convenience\ProductEditRequest;
use App\Http\Requests\Convenience\ProductSaleRequest;

class ProductController extends Controller
{
    // 商品出品処理（商品の投稿）
    public function createProduct(ProductSaleRequest $request)
    {
        try {
            // 未認証の場合
            if (!auth()->check()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            // 出品する商品情報の登録
            $product = new Product();
            $product->name = $request->name; // 商品名
            $product->price = $request->price; // 価格
            $product->category_id = $request->category; // 商品カテゴリ
            $product->expiration_date = Carbon::parse($request->expiration_date); // 賞味期限
            $user = Auth::user(); // 認証済みユーザーの取得
            $convenienceId = $user->convenience->id; // コンビニIDの取得
            $product->convenience_store_id = $convenienceId; // 商品情報とコンビニIDの紐付け
            $product->save();
            // 商品画像ファイルのアップロード処理
            $productPicture = $request->file('product_picture'); // 商品画像
            $dir = 'product_pictures'; // アップロード先S3フォルダ名
            $s3Upload = Storage::disk('s3')->putFile('/'.$dir, $productPicture); // S3にファイルを保存
            $s3Url = Storage::disk('s3')->url($s3Upload); // アップロードファイルURLを取得
            $s3UploadFileName = explode("/", $s3Url)[5]; // $s3UrlからS3でのファイル保存名取得
            $s3Path = $dir.'/'.$s3UploadFileName; // アップロード先パスを取得
            // 商品画像の保存
            $productPicture = new ProductPicture();
            $productPicture->product_id = $product->id; // 商品IDとコンビニIDの紐付け
            $productPicture->file = 'https://haikishare.com/' . $s3Path; // 商品画像ファイル名を保存
            $productPicture->save();
            $product->load('pictures'); // pictureリレーションをロード
            return response()->json(['message' => '商品の出品に成功しました。', 'product' => $product], 200);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => '商品の出品に失敗しました。'], 500);
        }
    }

    // 商品編集・更新処理
    public function editProduct(ProductEditRequest $request, $productId)
    {
        try {
            // 未認証の場合
            if (!auth()->check()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            // 出品する商品情報の更新
            $product = Product::findOrFail($productId);
            $product->name = $request->input('name'); // 商品名
            $product->price = $request->input('price'); // 価格
            $product->category_id = $request->input('category'); // 商品カテゴリ
            $product->expiration_date = Carbon::parse($request->input('expiration_date')); // 賞味期限
            $user = Auth::user(); // 認証済みユーザーの取得
            $convenienceId = $user->convenience->id; // コンビニIDの取得
            $product->convenience_store_id = $convenienceId; // 商品情報とコンビニIDの紐付け
            $product->save();
            // 商品画像の処理
            if ($request->hasFile('product_picture')) {
                $picture = $request->file('product_picture'); // 商品画像
                $extension = $picture->getClientOriginalExtension(); // ファイルの拡張子を取得
                $fileName = sha1($picture->getClientOriginalName()) . '.' . $extension; // SHA-1ハッシュでファイル名を決定
                $picturePath = $picture->storeAs('public/product_pictures', $fileName); // ファイルを保存
                // 商品画像をproduct_picturesテーブルに保存
                $ProductPicture = ProductPicture::where('product_id', $product->id)->first(); // 商品IDに紐づいた商品画像の取得
                $ProductPicture->file = $fileName; // 商品画像ファイル名を更新
                $ProductPicture->save();
            }
            $product->load('pictures'); // pictureリレーションをロード
            return response()->json(['message' => '商品の編集に成功しました。', 'product' => $product]);
        } catch (\Exception $e) {
            return response()->json(['message' => '商品の編集に失敗しました。'], 500);
        }
    }

    // 商品削除処理
    public function deleteProduct(Request $request, $productId)
    {
        try {
            // 未認証の場合
            if (!auth()->check()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            // 商品を論理削除する
            $product = Product::find($productId);
            // 商品が見つからない場合
            if (!$product) {
                return response()->json(['error' => '商品が見つかりません。'], 404);
            }
            $product->delete();
            // 商品画像を論理削除する
            $productPicture = ProductPicture::where('product_id', $productId)->first();
            if ($productPicture) {
                $productPicture->delete();
            }
            return response()->json(['message' => '商品削除が完了しました。', 'product' => $product], 200);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => '商品を削除できませんでした。'], 500);
        }
    }

    // 出品した商品情報の取得
    public function getConvenienceProducts(Request $request)
    {
        try {
            // 未認証の場合
            if (!auth()->check()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            // 認証済みユーザーの取得
            $user = Auth::user();
            $convenience = $user->convenience; // 関連付けられたコンビニ情報の取得
            if (!$convenience) { // コンビニ情報がない場合
                return response()->json(['error' => 'コンビニが見つかりません。'], 404);
            }
            // 商品情報と関連付けられた商品画像の中からコンビニIDと一致する商品を最新の投稿（降順）の順番で20件取得
            $products = Product::with(['pictures', 'category'])->withCount('likes')->where('convenience_store_id', $convenience->id)->orderBy('created_at', 'desc')->paginate(15);
            // 購入情報を取得し、各商品に購入状態is_purchasedプロパティを含める
            foreach ($products as $product) {
                $purchase = Purchase::where('product_id', $product->id)->first(); // 各商品に対して商品IDから購入情報を検索
                $purchasedId = $purchase ? $purchase->purchaser->id : null; // 購入情報が見つかった場合、購入者IDを取得
                $product->is_purchased = $purchase ? true : false; // 商品にis_purchasedプロパティを追加し、購入済みであればtrueを返す
                $product->purchased_id = $purchasedId; // 商品情報にpurchased_idプロパティを追加し、購入者IDをつける
            }
            return response()->json(['message' => '出品した商品を取得します。', 'products' => $products], 200);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => '出品した商品を取得できませんでした。'], 500);
        }
    }

    // 購入された商品情報の取得
    public function getPurchasedProducts(Request $request)
    {
        try {
            // 未認証の場合
            if (!auth()->check()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            // 認証済みユーザーの取得
            $user = Auth::user();
            $convenience = $user->convenience; // 関連付けられたコンビニ情報の取得
            if (!$convenience) { // コンビニ情報がない場合
                return response()->json(['error' => 'コンビニが見つかりません。'], 404);
            }
            // 商品情報と関連付けられた商品画像の中からコンビニIDと一致し、is_purchasedプロパティがtrueの商品を15件取得
            $products = Product::with(['pictures', 'category'])->withCount('likes')->where('convenience_store_id', $convenience->id)
                ->whereHas('purchases', function ($query) {
                    $query->where('is_purchased', true);
                })->paginate(15);
            return response()->json(['message' => '購入された商品を取得します。', 'products' => $products], 200);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => '購入された商品を取得できませんでした。'], 500);
        }
    }

    // すべての商品情報の取得（絞り込み検索機能含む）
    public function getAllProducts(SearchRequest $request)
    {
        try {
            // 認証済みユーザーIDの取得
            $userId = auth()->id();
            // 商品情報と関連付けられていた商品画像とカテゴリ情報をいいね数と合わせて取得
            $query = Product::with(['pictures', 'category'])->withCount('likes'); // withCountでいいね数の取得
            $prefecture = $request->input('prefecture'); // 出品しているコンビニがある都道府県
            if ($prefecture !== null) { // 検索条件にprefectureがある場合
                // Convenienceモデル経由でAddressモデルのprefectureの値を取得
                $query->whereHas('convenience.address', function ($addressQuery) use ($prefecture) {
                    // Addressモデルに指定された値とリクエストの値を持つレコードの検索
                    $addressQuery->where('prefecture', $prefecture);
                });
            }
            $minPrice = $request->input('minPrice'); // 最小価格
            if ($minPrice !== null) { // 検索条件にminPriceがある場合
                $query->where('price', '>=', $minPrice); // priceより小さい値を取得
            }
            $maxPrice = $request->input('maxPrice'); // 最大価格
            if ($maxPrice !== null) { // 検索条件にmaxPriceがある場合
                $query->where('price', '<=', $maxPrice); // priceより大きい値を取得
            }
            $expired = $request->input('expiration_date'); // 賞味期限切れかどうか
            if ($expired !== null) { // 検索条件に$expiredがある場合
                $today = Carbon::today()->toDateString(); // 現在の日付を取得
                if ($expired === 'true') { // $expiredがtrueの場合
                    $query->where('expiration_date', '<', $today); // （現在日付より古い）賞味期限切れの商品を検索
                } else {
                    $query->where('expiration_date', '>=', $today); // （現在日付より新しい）賞味期限内の商品を検索
                }
            }
            // カテゴリのIDを取得
            $categoryId = $request->input('category_id');
            if ($categoryId !== null) {
                $query->where('category_id', $categoryId);
            }
            $sortOrder = $request->sort ?? 'desc'; // デフォルトは降順
            $sortExpiredOrder = $request->input('sortExpired');
            // コレクションを並び替えて15件ずつ取得
            if ($sortExpiredOrder === 'asc' || $sortExpiredOrder === 'desc') {
                $products = $query->orderBy('expiration_date', $sortExpiredOrder)->orderBy('created_at', $sortOrder)->paginate(15);
            } else {
                $products = $query->orderBy('created_at', $sortOrder)->paginate(15);
            }
            // 各商品に対して処理を行う
            foreach ($products as $product) {
                // 商品情報にお気に入り情報を含める
                $like = Like::where('user_id', $userId)->where('product_id', $product->id)->first(); // Likeモデルから特定のユーザーがお気に入り登録した商品を取得
                $product->liked = $like ? true : false; // お気に入り登録（true）の場合はlikedプロパティをtrueにする
                // 購入済み商品は「購入済み」ラベルをつける
                $is_purchased = Purchase::where('product_id', $product->id)->first(); // Purchaseモデルから特定の商品が購入されているかどうか検索
                $product->is_purchased = $is_purchased ? true : false; // 購入済み（true）の場合はis_purchasedプロパティをtrueにする
            }
            return response()->json(['message' => 'すべての商品を取得します。', 'products' => $products], 200);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => 'すべての商品情報を取得できませんでした。'], 500);
        }
    }


    // 商品情報の取得処理
    public function getProduct(Request $request, $productId)
    {
        try {
            // 未認証の場合
            if (!auth()->check()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            // 認証済みユーザーIDの取得
            $userId = auth()->id();
            // 商品IDをもとに商品情報と関連付けられていた商品画像とカテゴリ情報とコンビニ情報をいいね数と合わせて取得
            $product = Product::with(['pictures', 'category', 'convenience', 'convenience.user', 'convenience.address'])
            ->withCount('likes')
            ->find($productId);
            \Log::info('商品は、', [$product]);
            // 商品が見つからない場合
            if (!$product) {
                return response()->json(['error' => '商品が見つかりません。'], 404);
            }
            // 購入情報を取得
            $purchase = Purchase::where('product_id', $productId)->first(); // Purchaseモデルから特定の商品IDを検索
            // 購入者IDを取得
            $purchasedId = $purchase ? $purchase->purchaser->id : null; // 購入情報に紐づく購入者IDを取得
            // is_purchasedプロパティを設定
            $product->is_purchased = $purchase ? true : false; // 購入情報があればis_purchasedプロパティをtrueにする
            $product->purchased_id = $purchasedId; // 購入者IDをpurchased_idにする
            // お気に入り情報を取得
            $like = Like::where('user_id', $userId)->where('product_id', $product->id)->first(); // Likeモデルから特定のユーザーがお気に入り登録した商品を取得
            $product->liked = $like ? true : false; // お気に入り登録（true）の場合はlikedプロパティをtrueにする
            return response()->json(['message' => '商品情報を取得します。', 'product' => $product], 200);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => '商品情報を取得できませんでした。'], 500);
        }
    }

    // 未ログインユーザーの商品情報取得
    public function getProductDetail(Request $request, $productId)
    {
        try {
            // 商品IDをもとに商品情報と関連付けられていた商品画像とカテゴリ情報とコンビニ情報をいいね数と合わせて取得
            $product = Product::with(['pictures', 'category', 'convenience', 'convenience.user', 'convenience.address'])
            ->withCount('likes')
            ->find($productId);
            \Log::info('商品は、', [$product]);
            // 商品が見つからない場合
            if (!$product) {
                return response()->json(['error' => '商品が見つかりません。'], 404);
            }
            return response()->json(['message' => '商品情報を取得します。', 'product' => $product], 200);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => '商品情報を取得できませんでした。'], 500);
        }
    }

    // 商品カテゴリ情報の取得
    public function getCategories()
    {
        try {
            // Categoryモデルからすべてのカテゴリ情報を取得
            $categories = Category::all();
            return response()->json(['categories' => $categories, 200]);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => '商品カテゴリを取得できませんでした。'], 500);
        }
    }
}