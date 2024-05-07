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
                            <!-- 【TODO】 商品画像を複数枚あっても表示できるように画像スライダーを使う -->
                            <th><label for="product_picture" class="c-label">商品画像</label></th>
                            <td><img v-if="product.pictures && product.pictures.length > 0" :src="'/storage/product_pictures/' + product.pictures[0].file" alt="商品画像" class="c-product__picture"></td>
                        </tr>
                    </table>

                    <!-- コンビニ側の購入ボタンは自店舗・他店舗に限らず購入ボタンをクリックできないようにする -->
                    <div class="p-product__button">
                        <button class="c-button c-button__purchase" :disabled="true">購入する</button>
                    </div>
                </div>
            </div>
        </div>
        <a @click="$router.back()">前のページに戻る</a>
    </main>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            product: [],
            categories: [],
            errors: null
        };
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
    },
}
</script>