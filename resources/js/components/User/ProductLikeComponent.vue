<template>
    <main class="l-main">
        <div class="l-main__user">
            <h1 class="c-title u-mb__xl">利用者お気に入り登録商品一覧</h1>
            <div class="l-main__wrapper">
                <div class="p-product__index p-product__index--like">
                    <!-- 全体件数と1ページの表示件数を表示 -->
                    <p class="c-text">{{ products.data ? products.data.length : 0 }}件表示 / 全{{ products.total ? products.total : 0 }}件中</p>
                    <ul class="p-product__list">
                        <!-- お気に入り登録商品がない場合 -->
                        <li v-if="!products.data || products.data.length === 0" class="p-product__item">
                            <p class="c-text u-pd__xl">お気に入り登録された商品はありません。</p>
                        </li>
                        <!-- お気に入り登録商品一覧を表示 -->
                        <li v-else v-for="product in products.data" :key="product.id" class="p-product__item">
                            <!-- 商品情報の表示 -->
                            <div class="c-card u-m__s">
                                <div class="p-card__header u-pd__s">
                                    <h3 class="c-card__name">{{ product.product.name }}</h3>
                                </div>
                                <div class="p-card__container">
                                    <img class="c-card__picture" :src="getProductPicturePath(product)" alt="商品画像">
                                    <label v-show="product.is_purchased" class="c-label__purchase">購入済み</label>
                                    <!-- いいねアイコン -->
                                    <div class="p-like p-like__content">
                                        <i v-if="!product.product.liked" class="c-icon c-icon__unlike far fa-heart" @click="productLike(product)"></i>
                                        <i v-else class="c-icon c-icon__like fas fa-heart" @click="productUnlike(product)"></i>
                                        <span>いいね{{ product.product.likes_count }}</span>
                                    </div>
                                    <p class="c-card__price">{{ product.product.price }}円</p>
                                    <p class="c-card__price">{{ formatDate(product.product.expiration_date) }}</p>
                                </div>
                                <div class="p-card__footer">
                                    <router-link :to="getProductDetailLink(product.product.id)" class="c-button c-button__user c-button__detail">詳細を見る</router-link>
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
    },

    methods: {
        // URLを作成する
        createURL(params) {
            console.log('検索URLを作成します');
            // URLの組み立て
            let url = `/user/products/liked`;

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
            console.log('お気に入り登録商品情報を取得します');
            // 現在のルートのクエリパラメータを取得
            const params = Object.assign({}, this.$route.query); // クエリパラメータのコピーを作成
            axios.get('/api/user/products/liked', { params: params }).then(response => {
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
            return { name: 'user.products.detail', params: { productId: productId } };
        },

        // 商品お気に入り登録
        productLike(product) {
            axios.post('/api/user/like/' + product.product.id).then(response => {
                console.log(product.product.id, 'の商品をお気に入り登録しました。');
                product.product.liked = true;
                console.log('this.likedは、', product.product.liked);
                product.product.likes_count++;
                console.log('product.product.likes_countは、', product.product.likes_count);
            }).catch(error => {
                console.error('商品のお気に入り登録失敗:', error);
            });
        },

        // 商品お気に入り解除
        productUnlike(product) {
            axios.post('/api/user/unlike/' + product.product.id).then(response => {
                console.log(product.product.id, 'の商品をお気に入り解除しました。');
                this.getProduct();
            }).catch(error => {
                console.error('商品のお気に入り解除失敗:', error);
            });
        },
    },
}
</script>