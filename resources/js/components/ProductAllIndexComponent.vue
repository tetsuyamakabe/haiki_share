<template>
    <main class="l-main">
        <div class="p-profile">
            <div class="p-product__form">
                <h1 class="c-title">商品一覧</h1>
                <div class="p-container">
                    <ul class="p-product__list">
                        <li v-for="product in products.data" :key="product.id" class="p-product__item">
                            <h3 class="c-product__name">{{ product.name }}</h3>
                            <div class="p-product__picture--container">
                                <img class="c-product__picture" :src="getProductPicturePath(product)" alt="Product Image">
                            </div>
                            <div>
                                <button v-if="!product.liked" type="button" class="btn btn-primary" @click="productLike(product)">いいね{{ likeCount }}</button>
                                <button v-else type="button" class="btn btn-primary" @click="productUnlike(product)">いいね{{ likeCount }}</button>
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
    </main>
</template>

<script>
import axios from 'axios';
import PaginationComponent from './PaginationComponent.vue'; // ページネーションコンポーネント

export default {
    components: {
        PaginationComponent,
    },

    data() {
        return {
            products: [],
            currentPage: 1,
            lastPage: 1,
            likeCount: 0,
        };
    },

    created() {
        this.getProduct(); // サーバから商品情報を取得
        this.getProducts(); // ページが変更された時の商品情報を取得
    },

    methods: {
        // 商品情報をサーバーから取得
        getProduct() {
            console.log('すべての商品情報を取得します');
            axios.get('/api/products').then(response => {
                console.log('APIからのレスポンス:', response.data);
                this.products = response.data.products; // プロパティ名を修正
                this.lastPage = response.data.last_page; // プロパティ名を修正
            }).catch(error => {
                console.error('商品情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // 商品画像のパスを取得するメソッド
        getProductPicturePath(product) {
            // console.log('productは、', product);
            if (product.pictures.length > 0) {
                return '/storage/product_pictures/' + product.pictures[0].file;
            } else {
                return '/storage/product_pictures/no_image.png';
            }
        },

        // 商品詳細画面のリンクを返すメソッド
        getProductDetailLink(productId) {
            // 【TODO】 利用者ユーザーは利用者側の詳細画面、コンビニユーザーはコンビニ側の詳細画面、ログインしていないユーザーはログイン画面に遷移させる
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
                const result = await axios.get('/api/products/' + `?page=${this.currentPage}`);
                const products = result.data;
                console.log('productsは、', products);
                this.products = products.products;
                console.log('productsは、', this.products);
                this.lastPage = products.products.last_page;
                console.log('this.lastPageは、', this.lastPage);
            } catch (error) {
                console.error('ページを更新時に商品情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            }
        },

        // 商品お気に入り登録
        productLike(product) {
            axios.post('/api/user/like/' + product.id).then(response => {
                console.log('お気に入り登録しました。');
                product.liked = true;
                console.log('product.likedは、', product.liked);
                this.likeCount = response.data.likeCount;
                console.log('product.likeCountは、', this.likeCount);
            }).catch(error => {
                console.error('商品のお気に入り登録失敗:', error);
            });
        },

        // 商品お気に入り解除
        productUnlike(product) {
            axios.post('/api/user/unlike/' + product.id).then(response => {
                console.log('お気に入り解除しました。');
                product.liked = false;
                console.log('product.likedは、', product.liked);
                this.likeCount = response.data.likeCount;
                console.log('product.likeCountは、', this.likeCount);
            }).catch(error => {
                console.error('商品のお気に入り解除失敗:', error);
            });
        },

        // 購入状態を更新するメソッド
        updatePurchaseStatus() {
            this.products.forEach(product => {
                this.getPurchase(product.id).then(response => {
                    product.purchaseStatus = response.data.status;
                }).catch(error => {
                    console.error('購入状態取得失敗:', error.response.data);
                });
            });
        },

        // 商品の購入状態を取得するメソッド
        getPurchase() {
            axios.get('/api/user/products/purchase/'+ this.productId).then(response => {
                this.isPurchased = response.data.status;
                console.log('APIからのレスポンス:', response.data);
            }).catch(error => {
                console.error('購入状態取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },
    },
}
</script>