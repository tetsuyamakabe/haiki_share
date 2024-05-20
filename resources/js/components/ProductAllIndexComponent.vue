<template>
    <main class="l-main">
        <h1 class="c-title u-mb__xl">商品一覧</h1>
        <div class="p-article">
            <div class="l-main__wrapper">

                <div class="p-product__index">
                    <!-- 全体件数と1ページの表示件数を表示 -->
                    <p class="c-text">{{ products.data ? products.data.length : 0 }}件表示 / 全{{ products.total ? products.total : 0 }}件中</p>
                    <ul class="p-product__list">
                        <!-- 検索結果がない場合 -->
                        <li v-if="!products.data || products.data.length === 0" class="p-product__item">
                            <p class="c-text u-pd__xl">検索結果はありません。</p>
                        </li>
                        <!-- 商品一覧・検索結果を表示 -->
                        <li v-else v-for="product in products.data" :key="product.id" class="p-product__item">
                            <!-- 商品情報の表示 -->
                            <div class="c-card u-m__s">
                                <div class="p-card__header u-pd__s">
                                    <h3 class="c-card__name">{{ product.name }}</h3>
                                </div>
                                <div class="p-card__container">
                                    <img class="c-card__picture" :src="getProductPicturePath(product)" alt="商品画像">
                                    <label v-show="product.is_purchased" class="c-label__purchase u-pd__m">購入済み</label>
                                    <!-- いいねアイコン -->
                                    <div class="p-like p-like__content u-pdr__s">
                                        <i v-if="!product.liked" class="c-icon c-icon__unlike far fa-heart" @click="productLike(product)"></i>
                                        <i v-else class="c-icon c-icon__like fas fa-heart" @click="productUnlike(product)"></i>
                                        <span>いいね{{ product.likes_count }}</span>
                                    </div>
                                    <p class="c-card__price">{{ product.price }}円</p>
                                    <p class="c-card__price">{{ formatDate(product.expiration_date) }}</p>
                                </div>
                                <div class="p-card__footer">
                                    <router-link :to="getProductDetailLink(product.id)" class="c-button c-button__common c-button__detail u-pd__s u-m__s">詳細を見る</router-link>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
            <!-- 絞り込み検索フォーム -->
            <search-component @search="searchResult" />
        </div>
        <!-- ページネーション -->
        <pagination-component @onClick="onPageChange" :current_page="currentPage" :last_page="lastPage" />
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
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
            lastParams: [],
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
            this.$router.push(url).then(() => {
                this.getProduct(); // ページ遷移が完了した後にgetProductを呼び出す
            });
        },

        // ページが変更されたときの処理
        onPageChange(page) {
            console.log('onPageChangeメソッドのpageは、', page);
            if (this.currentPage !== page) { // 現在のページ番号と新しいページ番号が異なるか
                this.currentPage = page; // ページ番号を更新
                const params = Object.assign({}, this.$route.query);
                params.page = page; // 新しいページ番号にする
                this.createURL(params); // 新しいURLを生成して画面遷移
            }
        },

        // 検索結果を表示する
        searchResult(params) {
            console.log('searchResultのparamsは、', params);
            // 前回の検索条件が同じであればページ遷移を行わずに検索結果を再取得する
            if (JSON.stringify(params) === JSON.stringify(this.lastParams)) {
                this.getProduct(params); // 前回と同じ検索条件での再取得
            } else {
                this.currentPage = 1; // ページ番号をリセット
                this.createURL(params); // 新しいURLを生成して画面遷移
            }
            this.lastParams = params; // 最後の検索条件を更新
        },

        // 商品情報をサーバーから取得
        getProduct() {
            console.log('すべての商品情報を取得します');
            // 現在のルートのクエリパラメータを取得
            const params = Object.assign({}, this.$route.query); // クエリパラメータのコピーを作成
            console.log('paramsは、', params, 'this.currentPageは、', this.currentPage);
            axios.get('/api/products', { params: params }).then(response => {
                console.log('curent_pageは、', response.data.products.current_page);
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
            // ログインユーザーのroleによって利用者・コンビニのマイページリンクを動的に変える
            if (this.$store.getters['auth/role'] === 'user') {
                    return { name: 'user.products.detail', params: { productId: productId } };
                } else if (this.$store.getters['auth/role'] === 'convenience') {
                    return { name: 'convenience.products.detail', params: { productId: productId } };
                }
            return "/home";
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
    },
}
</script>