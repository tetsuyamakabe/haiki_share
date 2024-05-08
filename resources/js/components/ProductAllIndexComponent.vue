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
                    <search-component @search="onPageChange" :current_page="currentPage" :last_page="lastPage"/>
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
        this.getProducts(); // ページが変更された時の商品情報を取得
    },

    methods: {
        // 商品情報をサーバーから取得
        getProduct() {
            console.log('すべての商品情報を取得します');
            axios.get('/api/products').then(response => {
                console.log('APIからのレスポンス:', response.data);
                this.products = response.data.products;
                this.lastPage = response.data.last_page;
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

        // ページが変更されたときの処理
        onPageChange(page, params) {
            this.getProducts(page, params);
        },

        // ページが変更されたときに新しい商品データを取得するメソッド
        // async getProducts(page, search) {
        //     try {
        //         console.log('検索結果を表示します');
        //         let url = '/api/products?';
        //         if (search.selectedPrefecture) {
        //             url += `prefecture=${this.selectedPrefecture}&`;
        //         }
        //         if (search.minPrice) {
        //             url += `minprice=${this.minPrice}&`;
        //         }
        //         if (search.maxPrice) {
        //             url += `maxprice=${this.maxPrice}&`;
        //         }
        //         if (search.isExpired !== null) {
        //             url += `expiration_date=${this.isExpired}&`;
        //         }
        //         url = url.slice(0, -1); // 最後にくっついている余分な'&'を削除
        //         console.log('検索URL:', url);

        //         // APIリクエストの前にcurrentPageを更新する
        //         this.currentPage = page;
        //         console.log('pageは、', page);
        //         console.log('this.currentPageは、', this.currentPage);
        //         const result = await axios.get('/api/products/' + `?page=${this.currentPage}`, url);
        //         const products = result.data;
        //         console.log('APIの結果は、', products);
        //         this.products = products.products;
        //         console.log('ページネーションメソッドのproductsは、', this.products);
        //         this.lastPage = products.products.last_page;
        //         console.log('this.lastPageは、', this.lastPage);
        //     } catch (error) {
        //         console.error('ページを更新時に商品情報取得失敗:', error.response.data);
        //         this.errors = error.response.data;
        //     }
        // },

        // ページが変更されたときに新しい商品データを取得するメソッド
        async getProducts(page, params) {
            // try {
                this.currentPage = page;
                console.log('pageは、', page);
                console.log('this.currentPageは、', this.currentPage);
                console.log('検索結果を表示します', params);

                // URLの組み立て
                let url = '/products?page=' + this.currentPage;
                if (params && params.prefecture) {
                    url += `&prefecture=${params.prefecture}`;
                }
                if (params && params.minprice) {
                    url += `&minprice=${params.minprice}`;
                }
                if (params && params.maxprice) {
                    url += `&maxprice=${params.maxprice}`;
                }
                if (params && params.expiration_date !== null) {
                    url += `&expiration_date=${params.expiration_date}`;
                }
                console.log('検索URL:', url);

                // ページ遷移
                await this.$router.push({ path: url });

                // APIリクエスト
                const result = await axios.get('/api' + url);
                console.log(result);
                const products = result.data;
                console.log('APIの結果は、', products);
                this.products = products.products;
                console.log('ページネーションメソッドのproductsは、', this.products);
                this.lastPage = products.products.last_page;
                console.log('this.lastPageは、', this.lastPage);
            // } catch (error) {
            //     console.error('ページを更新時に商品情報取得失敗:', error.response.data);
            //     this.errors = error.response.data;
            // }
        },

        // // 商品絞り込み検索
        // searchResult(search) {
        //     console.log('searchResult()メソッドです');
        //     axios.get('/api/products/', search).then(response => {
        //         const result = response.data;
        //         console.log('APIからのレスポンス:', response.data);
        //         this.products = result.products;
        //         this.lastPage = result.products.last_page;
        //         console.log('ページネーションメソッドのproductsは、', this.products);
        //         console.log('this.lastPageは、', this.lastPage);
        //     }).catch(error => {
        //         console.error('検索失敗:', error.response.data);
        //         this.errors = error.response.data;
        //     });
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
