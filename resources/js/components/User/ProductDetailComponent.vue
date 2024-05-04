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

                    <!-- Twitterのシェアボタン -->
                    <div class="twitter_share">
                        <button @click="twitterShare">ツイッターでシェアする</button>
                    </div>

                    <!-- 利用者側の購入ボタンは購入済みの購入ボタンは購入できない・自分が購入した商品の場合は、「購入をキャンセルする」ボタンが表示される -->
                    <div class="p-product__button">
                        <button v-if="isPurchased === '購入する'" class="c-button c-button__purchase" @click="purchaseProduct">購入する</button>
                        <button v-else-if="isPurchased === 'キャンセルする'" class="c-button c-button__cancel" @click="cancelPurchase">購入をキャンセルする</button>
                        <button v-else="isPurchased === '購入済み'" class="c-button c-button__purchased">購入済み</button>
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
            isPurchased: false, // 購入ボタン
            errors: null
        };
    },

    created() {
        this.productId = this.$route.params.productId; // ルートからproductIdを取得
        this.getProduct(); // 商品情報を取得
        this.getPurchase(); // 購入状態を取得
    },

    methods: {
        // 商品の購入状態の取得
        getPurchase() {
            axios.get('/api/user/products/purchase/'+ this.productId).then(response => {
                this.isPurchased = response.data.status;
                console.log('APIからのレスポンス:', response.data);
            }).catch(error => {
                console.error('購入状態取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

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
        }
    }
}
</script>

<style scoped>
.twitter_share{
    max-width: 1000px;
    margin: auto;
}
</style>