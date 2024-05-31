<template>
    <main class="l-main">
        <h1 class="c-title u-mb__xl">商品詳細</h1>
        <section class="l-main__wrapper u-pd__l">
            <div class="l-container">

                <h1 class="c-title">{{ product.name }}</h1> <!-- 商品名 -->
                <div class="p-product">
                    <!-- いいねアイコン -->
                    <div class="p-icon u-pdr__s">
                        <i v-if="!product.liked" class="c-icon c-icon__unlike far fa-heart" @click="productLike(product)"></i>
                        <i v-else class="c-icon c-icon__like fas fa-heart" @click="productUnlike(product)"></i>
                        <span>いいね{{ product.likes_count }}</span> <!-- いいね数 -->
                    </div>
                    <!-- エックスのシェアボタン -->
                    <div class="p-icon">
                        <button class="c-button c-button__share u-pd__s" @click="Xshare"><i class="fa-brands fa-x-twitter c-icon__share">でシェアする</i></button>
                    </div>
                    <!-- 商品画像 -->
                    <div class="p-product__picture">
                        <img class="c-product__picture--detail" :src="getProductPicturePath(product)" alt="商品画像">
                    </div>
                </div>
                <!-- 商品情報 -->
                <div class="p-product__detail">
                    <p class="c-card__price u-mt__s u-mb__s">価格：{{ product.price }}円</p>
                    <p class="c-card__category u-mt__s u-mb__s">カテゴリ：{{ getCategoryName(product.category_id) }}</p>
                    <p class="c-card__date u-mt__s u-mb__s">賞味期限：{{ formatDate(product.expiration_date) }}</p>
                </div>

            </div>

            <!-- 利用者側の購入ボタンは購入済みの購入ボタンは購入できない・自分が購入した商品の場合は、「購入をキャンセルする」ボタンが表示される -->
            <button v-if="product.is_purchased === false" class="c-button c-button__submit c-button__user u-pd__s" @click="purchaseProduct">購入する</button>
            <button v-else-if="product.is_purchased === true && product.purchased_id === loginId" class="c-button c-button__submit c-button__user u-pd__s" @click="cancelPurchase">購入をキャンセルする</button>
            <button v-else class="c-button c-button__submit c-button__user u-pd__s">購入済み</button>

        </section>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            product: {}, // 商品情報
            categories: [], // カテゴリ
            errors: null // エラーメッセージ
        };
    },

    computed: {
        // ログインユーザーかどうか
        isLogin() {
            return this.$store.getters['auth/check'];
        },

        // ログインユーザーのIDを取得
        loginId() {
            if (this.isLogin) {
                return this.$store.getters['auth/id'];
            } else {
                return null;
            }
        },
    },

    created() {
        this.productId = this.$route.params.productId; // ルートからproductIdを取得
        this.getProduct(); // インスタンス初期化時に商品情報を読み込む
    },

    methods: {
        // 商品情報をサーバーから取得
        getProduct() {
            // 商品情報取得APIをGET送信
            axios.get('/api/products/'+ this.productId).then(response => { // 商品IDを含むリクエスト
                // レスポンスデータをそれぞれのプロパティにセット
                this.product = response.data.product; // 商品情報
                this.category = response.data.product.category; // カテゴリ情報
            }).catch(error => {
                console.error('商品情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // カテゴリIDからカテゴリ名を取得するメソッド
        getCategoryName(categoryId) {
            if (this.category && this.category.id === categoryId) { // カテゴリ情報がある場合はcategoryIdと一致するidがあるか
                return this.category.name; // カテゴリ名を返す
            }
            return ''; // ない場合は空文字を返す
        },

        // 商品画像のパスを取得するメソッド
        getProductPicturePath(product) {
            console.log('productは、', product);
            console.log('product.picturesは、', product.pictures);
            if (product.pictures.length > 0) {
                return 'https://haikishare.com/product_pictures/' + product.pictures[0].file; // 商品画像がある場合は、その画像パスを返す
            } else {
                return 'https://haikishare.com/product_pictures/no_image.png'; // 商品画像がない場合は、デフォルトの商品画像のパスを返す
            }
        },

        // 賞味期限日付をフォーマットするメソッド
        formatDate(dateString) {
            const date = new Date(dateString); // Dateオブジェクトに変換する
            const year = date.getFullYear(); // 年数を取得
            const month = ('0' + (date.getMonth() + 1)).slice(-2); // 月数を取得、1桁の場合は2桁の数値に変換
            const day = ('0' + date.getDate()).slice(-2); // 日数を取得、1桁の場合は2桁の数値に変換
            return `${year}年${month}月${day}日`; // 年月日のフォーマットされた賞味期限日付を返す
        },

        // エックスのシェアボタン
        Xshare(){
            // エックスの投稿に遷移して商品を不特定多数の人がシェアできるようにする
            const shareURL = 'https://twitter.com/intent/tweet?text=' + "haiki share 商品をシェアする" + "%20%23haikishare" + '&url=' + "https://haikishare.com/user/products/detail/" + this.productId;  
            location.href = shareURL
        },

        // 商品購入するメソッド
        purchaseProduct() {
            // 商品購入APIをPOST送信
            axios.post('/api/user/products/purchase/' + this.productId).then(response => {
                this.getProduct(); // 購入状態を更新（「購入する」から「購入をキャンセル」へ変更）
            }).catch(error => {
                console.log('errorは、', error);
                console.error('商品購入処理失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        },

        // 商品購入キャンセルするメソッド
        cancelPurchase() {
            // 商品キャンセルAPIをPOST送信
            axios.delete('/api/user/products/purchase/cancel/' + this.productId).then(response => {
                this.getProduct();　// 購入状態を更新（「購入キャンセル」から「購入する」へ変更）
            }).catch(error => {
                console.log('errorは、', error);
                console.error('商品購入キャンセル処理失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        },

        // 商品お気に入り登録
        productLike(product) {
            // お気に入り登録APIをPOST送信
            axios.post('/api/user/like/' + product.id).then(response => {
                product.liked = true; // いいねアイコンをtrueに切り替え
                product.likes_count++; // いいね数のインクリメント
            }).catch(error => {
                console.error('商品のお気に入り登録失敗:', error);
            });
        },

        // 商品お気に入り解除
        productUnlike(product) {
            // お気に入り解除APIをPOST送信
            axios.post('/api/user/unlike/' + product.id).then(response => {
                product.liked = false; // いいねアイコンをfalseに切り替え
                product.likes_count--; // いいね数のデクリメント
            }).catch(error => {
                console.error('商品のお気に入り解除失敗:', error);
            });
        },
    }
}
</script>