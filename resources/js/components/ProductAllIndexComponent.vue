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
                                <label v-show="product.is_purchased" class="c-label__purchase">購入済み</label>
                            </div>
                            <div>
                                <i v-if="!product.liked" class="far fa-heart" @click="productLike(product)"></i>
                                <i v-else class="fas fa-heart" @click="productUnlike(product)"></i>
                                <span>いいね{{ product.likes_count }}</span>
                            </div>
                            <p class="c-product__price">価格 {{ product.price }} 円</p>
                            <p class="c-product__date">賞味期限 {{ product.expiration_date }}</p>
                            <div class="p-product__button">
                                <router-link :to="getProductDetailLink(product.id)" class="c-button">詳細を見る</router-link>
                            </div>
                        </li>
                    </ul>
                    <!-- 絞り込み検索 -->
                    <search-component @search="searchResult" />
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
import SearchComponent from './SearchComponent.vue'; // 絞り込み検索コンポーネント
import PaginationComponent from './PaginationComponent.vue'; // ページネーションコンポーネント

export default {
    components: {
        SearchComponent,
        PaginationComponent,
    },

    data() {
        return {
            products: [],
            currentPage: 1,
            lastPage: 1,
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
        // this.getProducts(); // ページが変更された時の商品情報を取得
    },

    methods: {
        // URLを作成する
        createURL(params) {
            console.log('検索URLを作成します');
            // URLの組み立て
            let url = `/products`;

            if (params && params.page) {
                url += `?page=${params.page}`;
            } else {
                url += `?page=${this.currentPage}`;
            }

            if (params && params.prefecture) {
                url += `&prefecture=${params.prefecture}`;
            }
            if (params && params.minprice) {
                url += `&minprice=${params.minprice}`;
            }
            if (params && params.maxprice) {
                url += `&maxprice=${params.maxprice}`;
            }
            if (params && params.expiration_date) {
                url += `&expiration_date=${params.expiration_date}`;
            }
            console.log('検索URL:', url);
            // ページ遷移
            this.$router.push({ path: url });
            this.getProduct();
        },

        // ページが変更されたときの処理
        onPageChange(page) {
            console.log('これはonPageChange()メソッドです。');
            this.currentPage = page; // ページ番号を更新
            this.createURL();
            console.log('これはonPageChange()メソッドでした。');
        },

        // 検索結果を表示する
        searchResult(params) {
            this.createURL(params);
        },

        // 商品情報をサーバーから取得
        getProduct() {
            console.log('すべての商品情報を取得します');
            // 現在のルートのクエリパラメータを取得
            const params = this.$route.query;
            console.log('paramsは、', params);
            axios.get('/api/products', { params: params }).then(response => {
                console.log('paramsは、', params);
                console.log('getProductのAPIからのレスポンス:', response.data);
                this.products = response.data.products;
                console.log('productsは、', this.products);
                this.lastPage = response.data.products.last_page;
                console.log('this.lastPageは、', this.lastPage);
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
            // ログインユーザーのroleによって利用者・コンビニのマイページリンクを動的に変える
            if (this.$store.getters['auth/role'] === 'user') {
                    return { name: 'user.products.detail', params: { productId: productId } };
                } else if (this.$store.getters['auth/role'] === 'convenience') {
                    return { name: 'convenience.products.detail', params: { productId: productId } };
                }
            return "/home";
        },

        // // ページが変更されたときに新しい商品データを取得するメソッド
        // async getProducts(page) {
        //     try {
        //         // APIリクエストの前にcurrentPageを更新する
        //         this.currentPage = page;
        //         console.log('pageは、', page);
        //         console.log('this.currentPageは、', this.currentPage);
        //         const result = await axios.get(`/api/products?page=${page}`);
        //         const products = result.data;
        //         console.log('productsは、', products);
        //         this.products = products.products;
        //         console.log('productsは、', this.products);
        //         this.lastPage = products.products.last_page;
        //         console.log('this.lastPageは、', this.lastPage);
        //     } catch (error) {
        //         console.error('ページを更新時に商品情報取得失敗:', error.response.data);
        //         this.errors = error.response.data;
        //     }
        // },

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
    },
}
</script>