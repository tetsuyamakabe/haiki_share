<template>
    <main class="l-main">
        <h1 class="c-title u-mb__xl">コンビニ出品した商品一覧</h1>
        <div class="l-main__wrapper">

            <div class="p-product__index p-product__index--sale">
                <!-- 全体件数と1ページの表示件数を表示 -->
                <p class="c-text">{{ products.data ? products.data.length : 0 }}件表示 / 全{{ products.total ? products.total : 0 }}件中</p>
                <ul class="p-product__list">
                    <!-- 出品した商品がない場合 -->
                    <li v-if="!products.data || products.data.length === 0" class="p-product__item">
                        <p class="c-text u-pd__xl">出品した商品はありません。</p>
                    </li>
                    <!-- 出品した商品一覧を表示 -->
                    <li v-else v-for="product in products.data" :key="product.id" class="p-product__item">
                        <!-- 商品情報の表示 -->
                        <div class="c-card u-m__s">
                            <div class="p-card__header u-pd__s">
                                <h3 class="c-card__name">{{ product.name }}</h3> <!-- 商品名 -->
                            </div>
                            <div class="p-card__container">
                                <img class="c-card__picture u-mb__s" :src="getProductPicturePath(product)" alt="商品画像"> <!-- 商品画像 -->
                                <p class="c-card__price">{{ product.price }}円</p> <!-- 価格 -->
                                <p class="c-card__price">{{ formatDate(product.expiration_date) }}</p> <!-- 賞味期限 -->
                            </div>
                            <div class="p-card__footer">
                                <router-link :to="getProductDetailLink(product.id)" class="c-button c-button__convenience c-button__detail u-pd__s u-m__s">詳細を見る</router-link>
                                <router-link v-if="!product.is_purchased" :to="getProductEditLink(product.id)" class="c-button c-button__convenience c-button__edit u-pd__s u-m__s">編集する</router-link>
                            </div>
                        </div>
                    </li>
                </ul>
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
        PaginationComponent, // ページネーションコンポーネント
    },

    data() {
        return {
            products: [], // 商品情報
            currentPage: 1, // 現在ページ
            lastPage: 1, // 最後のページ
        };
    },

    computed: {
        // ログインユーザーかどうか
        isLogin() {
            return this.$store.getters['auth/check'];
        },
    },

    created() {
        this.getSaleProduct(); // インスタンス初期化時に出品した商品情報を読み込む
    },

    methods: {
        // URLを作成する
        createURL(params) {
            console.log('検索URLを作成します');
            // URLの組み立て
            let url = `/convenience/products/sale`;
            if (params && params.page) { // パラメータとパラメータのpageがある場合
                url += `?page=${params.page}`; // urlにparams.pageを追加
            } else {
                url += `?page=${this.currentPage}`; // urlにthis.currentPageを追加
            }
            console.log('検索URL:', url);
            // ページ遷移
            this.$router.push(url).then(() => {
                this.getSaleProduct(); // ページ遷移が完了した後にgetSaleProduct()メソッドを呼び出す
            });
        },

        // ページが変更されたときの処理
        onPageChange(page) {
            console.log('onPageChangeメソッドのpageは、', page);
            if (this.currentPage !== page) { // 現在のページ番号と新しいページ番号が異なるか
                this.currentPage = page; // ページ番号を更新
                const params = Object.assign({}, this.$route.query); // 新しいクエリパラメータをparamsオブジェクトにコピー
                params.page = page; // 新しいページ番号にする
                this.createURL(params); // 新しいURLを生成して画面遷移
            }
        },

        // 出品した商品情報をサーバーから取得
        getSaleProduct() {
            // 現在のルートのクエリパラメータを取得
            const params = Object.assign({}, this.$route.query); // クエリパラメータのコピーを作成
            // 出品した商品情報取得APIをGET送信
            axios.get('/api/convenience/products', { params: params }).then(response => { // パラメータを含むリクエスト
                // レスポンスデータをそれぞれのプロパティにセット
                this.products = response.data.products; // 出品した商品情報
                this.lastPage = response.data.products.last_page; // ページ数
            }).catch(error => {
                console.error('商品情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // 商品画像のパスを取得するメソッド
        getProductPicturePath(product) {
            if (product.pictures.length > 0) {
                return '/storage/product_pictures/' + product.pictures[0].file; // 商品画像がある場合は、その画像パスを返す
            } else {
                return '/storage/product_pictures/no_image.png'; // 商品画像がない場合は、デフォルトの商品画像のパスを返す
            }
        },

        // 賞味期限日付をフォーマットするメソッド
        formatDate(dateString) {
            const date = new Date(dateString); // Dateオブジェクトに変換する
            const year = date.getFullYear(); // 年数を取得
            const month = ('0' + (date.getMonth() + 1)).slice(-2); // 月数を取得、1桁の場合は2桁の数値に変換
            const day = ('0' + date.getDate()).slice(-2); // 日数を取得、1桁の場合は2桁の数値に変換
            return `${year}年${month}月${day}日`; // 年月日のフォーマットされた賞味期限日付を返す
        },

        // 商品詳細画面のリンクを返すメソッド
        getProductDetailLink(productId) {
            return { name: 'convenience.products.detail', params: { productId: productId } }; // 商品詳細画面のリンクを返す
        },

        // 商品編集画面のリンクを返すメソッド
        getProductEditLink(productId) {
            return { name: 'convenience.products.edit', params: { productId: productId } }; // 商品編集画面のリンクを返す
        },
    },
}
</script>