<template>
    <main class="l-main">
        <div class="p-profile">
            <div class="p-product__form">
                <h1 class="c-title">出品した商品一覧</h1>
                <div class="p-container">
                    <ul class="p-product__list">
                        <li v-for="product in products.data" :key="product.id" class="p-product__item">
                            <h3 class="c-product__name">{{ product.name }}</h3>
                            <div class="p-product__picture--container">
                                <img class="c-product__picture" :src="getProductPicturePath(product)" alt="Product Image">
                            </div>
                            <p class="c-product__price">価格 {{ product.price }} 円</p>
                            <p class="c-product__date">賞味期限 {{ product.expiration_date }}</p>
                            <div class="p-product__button">
                                <router-link :to="getProductDetailLink(product.id)" class="c-button">詳細を見る</router-link>
                                <router-link :to="getProductEditLink(product.id)" class="c-button">編集する</router-link>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- ページネーション -->
                <pagination-component :current_page="currentPage" :last_page="lastPage" :convenienceId="convenienceId" />
            </div>
        </div>
    </main>
</template>

<script>
import PaginationComponent from '../PagenationComponent.vue'

export default {
    components: {
        PaginationComponent,
    },
    data() {
        return {
            products: [],
            currentPage: 1, // currentPageを定義
            lastPage: 1, // lastPageを定義
        };
    },

    created() {
        this.convenienceId = this.$route.params.convenienceId; // ルートからconvenienceIdを取得
        this.getProduct(); // 商品情報を取得
    },

    methods: {
        getProduct() {
            console.log('商品情報を取得します');
            axios.get('/api/convenience/products/' + this.convenienceId).then(response => {
                this.products = response.data.product;
                console.log('商品一覧:', this.products);
                console.log('APIからのレスポンス:', response.data);
                this.lastPage = response.data.product.last_page;
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
            return { name: 'convenience.products.detail', params: { productId: productId } };
        },

        getProductEditLink(productId) {
            return { name: 'convenience.products.edit', params: { productId: productId } };
        },

    },
}
</script>
