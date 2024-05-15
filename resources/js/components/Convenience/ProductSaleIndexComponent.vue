<template>
    <main class="l-main">
        <div class="l-main__convenience">
            <h1 class="c-title u-mb__xl">コンビニ出品した商品一覧</h1>
            <div class="l-main__wrapper">
                <div class="p-product__index p-product__index--sale">
                    <ul class="p-product__list">
                        <!-- 出品した商品がない場合 -->
                        <li v-if="!products || products.data.length === 0" class="p-product__item">
                            <p class="c-text u-pd__xl">出品した商品はありません。</p>
                        </li>
                        <!-- 出品した商品一覧を表示 -->
                        <li v-else v-for="product in products.data" :key="product.id" class="p-product__item">
                            <!-- 商品情報の表示 -->
                            <div class="c-card u-m__s">
                                <div class="p-card__header u-pd__s">
                                    <h3 class="c-card__name">{{ product.name }}</h3>
                                </div>
                                <div class="p-card__container">
                                    <img class="c-card__picture" :src="getProductPicturePath(product)" alt="商品画像">
                                    <p class="c-card__price">{{ product.price }}円</p>
                                    <p class="c-card__price">{{ formatDate(product.expiration_date) }}</p>
                                </div>
                                <div class="p-card__footer">
                                    <router-link :to="getProductDetailLink(product.id)" class="c-button c-button__convenience c-button__detail">詳細を見る</router-link>
                                    <router-link :to="getProductEditLink(product.id)" class="c-button c-button__convenience c-button__edit">編集する</router-link>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- ページネーション -->
        <pagination-component @onClick="onPageChange" :current_page="currentPage" :last_page="lastPage" />

        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
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
            currentPage: 1, // currentPageを定義
            lastPage: 1, // lastPageを定義
        };
    },

    computed: {
        // ログインユーザーかどうか
        isLogin() {
            return this.$store.getters['auth/check'];
        },
    },

    created() {
        this.getProduct(); // サーバから商品情報を取得
    },

    methods: {
        // URLを作成する
        createURL(params) {
            console.log('検索URLを作成します');
            // URLの組み立て
            let url = `/convenience/products/sale`;

            if (params && params.page) {
                url += `?page=${params.page}`;
            } else {
                url += `?page=${this.currentPage}`;
            }

            console.log('検索URL:', url);
            // ページ遷移
            // if (this.$route.fullPath !== url) { // 現在のURLと新しいURLが異なるか
                this.$router.push(url);
                this.getProduct();
            // }
        },

        // ページが変更されたときの処理
        onPageChange(page) {
            if (this.currentPage !== page) { // 現在のページ番号と新しいページ番号が異なるか
                this.currentPage = page; // ページ番号を更新
                const params = Object.assign({}, this.$route.query);
                params.page = page; // 新しいページ番号にする
                this.createURL(params); // 新しいURLを生成して画面遷移
            }
        },

        // 商品情報をサーバーから取得
        getProduct() {
            console.log('出品した商品情報を取得します');
            // 現在のルートのクエリパラメータを取得
            const params = Object.assign({}, this.$route.query); // クエリパラメータのコピーを作成
            axios.get('/api/convenience/products', { params: params }).then(response => {
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
            if (product.pictures.length > 0) {
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

        // 商品詳細画面のリンクを返すメソッド
        getProductDetailLink(productId) {
            return { name: 'convenience.products.detail', params: { productId: productId } };
        },

        // 商品編集画面のリンクを返すメソッド
        getProductEditLink(productId) {
            return { name: 'convenience.products.edit', params: { productId: productId } };
        },
    },
}
</script>