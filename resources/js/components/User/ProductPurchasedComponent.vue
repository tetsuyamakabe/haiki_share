<template>
    <main class="l-main">
        <div class="p-profile">
            <div class="p-product__form">
                <h1 class="c-title">購入した商品一覧</h1>
                <div class="p-container">
                    <ul class="p-product__list">
                        <li v-for="product in products" :key="product.id" class="p-product__item">
                            <h3 class="c-product__name">{{ product.name }}</h3>
                            <div class="p-product__picture--container">
                                <img class="c-product__picture" :src="getProductPicturePath(product)" alt="Product Image">
                            </div>
                            <p class="c-product__price">価格 {{ product.price }} 円</p>
                            <p class="c-product__date">賞味期限 {{ product.expiration_date }}</p>
                            <div class="p-product__button">
                                <router-link :to="getProductDetailLink(product.id)" class="c-button">詳細を見る</router-link>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- ページネーション -->
                <pagination-component @onClick="onPageChange" :current_page="currentPage" :last_page="lastPage" />
            </div>
        </div>
        <a @click="$router.back()">前のページに戻る</a>
    </main>
</template>

<script>
import axios from 'axios';
import PaginationComponent from '../PaginationComponent.vue'; // ページネーションコンポーネント

export default {
    components: {
        PaginationComponent,
    },

    data() {
        return {
            products: [],
            currentPage: 1,
            lastPage: 1,
        };
    },

    created() {
        this.getPurchasedProduct(); // サーバから商品情報を取得
        this.getProducts(); // ページが変更された時の商品情報を取得
    },

    methods: {
        // 商品情報をサーバーから取得
        getPurchasedProduct() {
            console.log('お気に入り登録商品情報を取得します');
            axios.get('/api/user/products/purchased').then(response => {
                this.products = response.data.products;
                console.log('商品一覧:', this.products);
                console.log('APIからのレスポンス:', response.data);
                this.lastPage = response.data.products.last_page;
            }).catch(error => {
                console.error('商品情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // 商品画像のパスを取得するメソッド
        getProductPicturePath(product) {
            // console.log('productは、', product);
            if (product.product.pictures.length > 0) {
                return '/storage/product_pictures/' + product.product.pictures[0].file;
            } else {
                return '/storage/product_pictures/no_image.png';
            }
        },

        // 商品詳細画面のリンクを返すメソッド
        getProductDetailLink(productId) {
            return { name: 'convenience.products.detail', params: { productId: productId } };
        },

        // ページが変更されたときの処理
        onPageChange(page) {
            this.getProducts(page);
        },

        // ページが変更されたときに新しい商品データを取得するメソッド
        async getProducts(page) {
            try {
                // APIリクエストの前にcurrentPageを更新する
                this.currentPage = page;
                console.log('pageは、', page);
                console.log('this.currentPageは、', this.currentPage);
                const result = await axios.get('/api/user/products/purchased' + `?page=${this.currentPage}`);
                const responseData = result.data;
                console.log('responseDataは、', responseData);
                this.products = responseData.products;
                console.log('this.productsは、', this.products);
                this.lastPage = responseData.products.last_page;
                console.log('this.lastPageは、', this.lastPage);
            } catch (error) {
                console.error('ページを更新時に商品情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            }
        },
    },
}
</script>