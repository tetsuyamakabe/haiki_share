<template>
    <main class="l-main">
        <div class="p-profile">
            <div class="p-product__form">
                <h1 class="c-title">商品詳細</h1>
                <div class="p-container">
                    <table>
                        <h1>{{ product.name }}</h1>
                        <tr>
                            <th><label for="price" class="c-label">価格</label></th>
                            <td>{{ product.price }}</td>
                        </tr>

                        <tr>
                            <th><label for="category" class="c-label">カテゴリ名</label></th>
                            <td>{{ getCategoryName(product.category_id) }}</td>
                        </tr>

                        <tr>
                            <th><label for="expiration_date" class="c-label">賞味期限</label></th>
                            <td>{{ product.expiration_date }}</td>
                        </tr>

                        <tr>
                            <th><label for="product_picture" class="c-label">商品画像</label></th>
                            <td>
                                <div class="p-product-slider">
                                    <img v-for="(picture, index) in product.pictures" :key="index" :src="'/storage/product_pictures/' + picture.file" :alt="'商品画像' + (index + 1)" class="c-product__picture">
                                </div>
                            </td>
                        </tr>
                    </table>

                    <div>
                        <i v-if="!product.liked" class="far fa-heart" @click="productLike(product)"></i>
                        <i v-else class="fas fa-heart" @click="productUnlike(product)"></i>
                        <span>いいね{{ product.likes_count }}</span>
                    </div>
                    <!-- Twitterのシェアボタン -->
                    <div class="twitter_share">
                        <button @click="twitterShare">ツイッターでシェアする</button>
                    </div>

                    <!-- 利用者側の購入ボタンは購入済みの購入ボタンは購入できない・自分が購入した商品の場合は、「購入をキャンセルする」ボタンが表示される -->
                    <div class="p-product__button">
                        <button v-if="product.is_purchased === false" class="c-button c-button__purchase" @click="purchaseProduct">購入する</button>
                        <button v-else-if="product.is_purchased === true && product.purchased_id === loginId" class="c-button c-button__cancel" @click="cancelPurchase">購入をキャンセルする</button>
                        <button v-else class="c-button c-button__purchased">購入済み</button>
                    </div>
                </div>
            </div>
        </div>
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
                console.log('APIからのレスポンス:', response.data);
            }).catch(error => {
                console.error('商品情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // カテゴリIDからカテゴリ名を取得するメソッド
        getCategoryName(categoryId) {
            for (let i = 0; i < this.categories.length; i++) {
                if (this.categories[i].id === categoryId) {
                    return this.categories[i].name;
                }
            }
            return '';
        },

        // 商品購入するメソッド
        purchaseProduct() {
            axios.post('/api/user/products/purchase/' + this.productId).then(response => {
                console.log('APIからのレスポンス:', response.data);
                this.getPurchase(); // 購入状態を更新（「購入する」から「購入をキャンセル」へ変更）
            }).catch(error => {
                console.error('商品購入処理失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        },

        // 商品購入キャンセルするメソッド
        cancelPurchase() {
            // 購入キャンセルを押した際にバックエンドに通知リクエストを送信する
            axios.delete('/api/user/products/purchase/cancel/' + this.productId).then(response => {
                console.log('APIからのレスポンス:', response.data);
                this.getPurchase();　// 購入状態を更新（「購入キャンセル」から「購入する」へ変更）
            }).catch(error => {
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

<style scoped>
.twitter_share{
    max-width: 1000px;
    margin: auto;
}
</style>