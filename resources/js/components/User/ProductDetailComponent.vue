<template>
    <main class="l-main">
        <div class="l-main__user">
            <h1 class="c-title u-mb__xl">商品詳細</h1>
                <section class="l-main__wrapper u-m__s">
                    <div class="l-container">
                        <h1 class="c-title">{{ product.name }}</h1>
                        <div class="p-product">
                            <!-- いいねアイコン -->
                            <div class="p-like p-like__content u-pdr__s">
                                <i v-if="!product.liked" class="c-icon c-icon__unlike far fa-heart" @click="productLike(product)"></i>
                                <i v-else class="c-icon c-icon__like fas fa-heart" @click="productUnlike(product)"></i>
                                <span>いいね{{ product.likes_count }}</span>
                            </div>
                            <!-- エックスのシェアボタン -->
                            <div class="p-like p-like__content">
                                <button class="c-button c-button__share u-pd__s" @click="twitterShare"><i class="fa-brands fa-x-twitter c-icon__share">でシェアする</i></button>
                            </div>

                            <div class="p-product__picture">
                                <!-- 商品画像 -->
                                <img class="c-product__picture--detail" :src="getProductPicturePath(product)" alt="商品画像">
                            </div>
                        </div>
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
            </div>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            product: {},
            categories: [],
            errors: null
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
        this.getProduct(); // 商品情報を取得
    },

    methods: {
        // 商品情報をサーバーから取得
        getProduct() {
            axios.get('/api/products/'+ this.productId).then(response => {
                this.product = response.data.product;
                this.category = response.data.product.category;
                console.log('APIからのレスポンス:', response.data);
                console.log('this.productは、', this.product);
                console.log('this.categoryは、', this.category);
            }).catch(error => {
                console.error('商品情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // カテゴリIDからカテゴリ名を取得するメソッド
        getCategoryName(categoryId) {
            if (this.category && this.category.id === categoryId) {
                return this.category.name;
            }
            return '';
        },

        // 商品画像のパスを取得するメソッド
        getProductPicturePath(product) {
            console.log('productは、', product);
            if (product.pictures && product.pictures.length > 0) {
                return '/storage/product_pictures/' + product.pictures[0].file;
            } else {
                return '/storage/product_pictures/no_image.png';
            }
        },

        // 日付をフォーマットするメソッド
        formatDate(dateString) {
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = ('0' + (date.getMonth() + 1)).slice(-2); // 月は 0 から始まるため +1
            const day = ('0' + date.getDate()).slice(-2);
            return `${year}年${month}月${day}日`;
        },

        // 商品購入するメソッド
        purchaseProduct() {
            axios.post('/api/user/products/purchase/' + this.productId).then(response => {
                console.log('APIからのレスポンス:', response.data);
                this.getProduct(); // 購入状態を更新（「購入する」から「購入をキャンセル」へ変更）
            }).catch(error => {
                console.log('errorは、', error);
                console.error('商品購入処理失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        },

        // 商品購入キャンセルするメソッド
        cancelPurchase() {
            // 購入キャンセルを押した際にバックエンドに通知リクエストを送信する
            axios.delete('/api/user/products/purchase/cancel/' + this.productId).then(response => {
                console.log('APIからのレスポンス:', response.data);
                this.getProduct();　// 購入状態を更新（「購入キャンセル」から「購入する」へ変更）
            }).catch(error => {
                console.log('errorは、', error);
                console.error('商品購入キャンセル処理失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        },

        // Twitterのシェアボタン
        twitterShare(){
            const shareURL = 'https://twitter.com/intent/tweet?text=' + "haiki share 商品をシェアする" + "%20%23haikishare" + '&url=' + "https://haikishare.com/user/products/detail/" + this.productId;  
            location.href = shareURL
        },

        // 商品お気に入り登録
        productLike(product) {
            axios.post('/api/user/like/' + product.id).then(response => {
                console.log(product.id, 'の商品をお気に入り登録しました。');
                product.liked = true;
                console.log('this.likedは、', product.liked);
                product.likes_count++;
                console.log('product.likes_countは、', product.likes_count);
            }).catch(error => {
                console.error('商品のお気に入り登録失敗:', error);
            });
        },

        // 商品お気に入り解除
        productUnlike(product) {
            axios.post('/api/user/unlike/' + product.id).then(response => {
                console.log(product.id, 'の商品をお気に入り解除しました。');
                product.liked = false;
                console.log('product.likedは、', product.liked);
                product.likes_count--;
                console.log('product.likes_count', product.likes_count);
            }).catch(error => {
                console.error('商品のお気に入り解除失敗:', error);
            });
        },

    }
}
</script>