<template>
    <main class="l-main">
        <h1 class="c-title u-mb__xl">お気に入り登録商品一覧</h1>
        <div class="p-article">
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
                                    <h3 class="c-card__name">{{ product.product.name }}</h3> <!-- 商品名 -->
                                </div>
                                <div class="p-card__container">
                                    <img class="c-card__picture u-mb__s" :src="getProductPicturePath(product.product)" alt="商品画像"> <!-- 商品画像 -->
                                    <label v-show="product.product.is_purchased" class="c-label__purchase u-pd__m">購入済み</label> <!-- 購入済みラベル -->
                                    <!-- いいねアイコン -->
                                    <div class="p-icon u-pdr__s">
                                        <i v-if="!product.product.liked" class="c-icon c-icon__unlike far fa-heart" @click="productLike(product)"></i>
                                        <i v-else class="c-icon c-icon__like fas fa-heart" @click="productUnlike(product)"></i>
                                        <span>いいね{{ product.product.likes_count }}</span> <!-- いいね数 -->
                                    </div>
                                    <p class="c-card__text">{{ product.product.price }}円</p> <!-- 価格 -->
                                    <p class="c-card__text">{{ formatDate(product.product.expiration_date) }}</p> <!-- 賞味期限 -->
                                </div>
                                <div class="p-card__footer">
                                    <div class="c-button__container">
                                        <router-link :to="getProductDetailLink(product.product.id)" class="c-button c-button__default u-pd__s u-m__s">詳細を見る</router-link>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
            <!-- サイドバー -->
            <sidebar-component :introduction="introduction"></sidebar-component>
        </div>
        <!-- ページネーション -->
        <pagination-component @onClick="onPageChange" :current_page="currentPage" :last_page="lastPage" />
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
import axios from 'axios';
import SidebarComponent from './SidebarComponent.vue';
import PaginationComponent from '../PaginationComponent.vue'; // ページネーションコンポーネント

export default {
    components: {
        SidebarComponent, // サイドバーコンポーネントを読み込み
        PaginationComponent, // ページネーションコンポーネント
    },

    data() {
        return {
            products: [], // 商品
            currentPage: 1, // 現在ページ
            lastPage: 1, // 最後のページ
            introduction: '', //自己紹介文
        };
    },

    computed: {
        // ログインユーザーかどうか
        isLogin() {
            return this.$store.getters['auth/check'];
        },
    },

    created() {
        this.getLikeProduct(); // インスタンス初期化時にお気に入り登録商品情報を読み込む
        this.getSidebarProfile(); // インスタンス初期化時にサイドバーに表示するプロフィール情報を読み込む
    },

    methods: {
        // URLを作成する
        createURL(params) {
            console.log('検索URLを作成します');
            // URLの組み立て
            let url = `/user/products/liked`;
            if (params && params.page) { // パラメータとパラメータのpageがある場合
                url += `?page=${params.page}`; // urlにparams.pageを追加
            } else {
                url += `?page=${this.currentPage}`; // urlにthis.currentPageを追加
            }
            console.log('検索URL:', url);
            // ページ遷移
            this.$router.push(url).then(() => {
                this.getLikeProduct(); // ページ遷移が完了した後にgetLikeProduct()メソッドを呼び出す
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

        // 商品情報をサーバーから取得
        getLikeProduct() {
            // 現在のルートのクエリパラメータを取得
            const params = Object.assign({}, this.$route.query); // クエリパラメータのコピーを作成
            console.log('paramsは、', params, 'this.currentPageは、', this.currentPage);
            // お気に入り登録商品情報取得APIをGET送信
            axios.get('/api/user/products/liked', { params: params }).then(response => { // パラメータを含むリクエスト
                console.log('APIからのレスポンス：', response.data);
                // レスポンスデータをそれぞれのプロパティにセット
                this.products = response.data.products; // お気に入り登録商品情報
                this.lastPage = response.data.products.last_page; // ページ数
            }).catch(error => {
                console.log(error);
                console.error('商品情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // 商品画像のパスを取得するメソッド
        getProductPicturePath(product) {
            console.log('productは、', product);
            console.log('product.picturesは、', product.pictures);
            if (product.pictures && product.pictures.length > 0) {
                return product.pictures[0].file; // 商品画像がある場合は、その画像パスを返す
            } else {
                return 'https://haikishare.com/product_pictures/no_image.png'; // 商品画像がない場合は、デフォルトの商品画像のパスを返す
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
            return { name: 'user.products.detail', params: { productId: productId } }; // 商品詳細画面のリンクを返す
        },

        // 商品お気に入り登録
        productLike(product) {
            // お気に入り登録APIをPOST送信
            axios.post('/api/user/like/' + product.id).then(response => {
                product.liked = true; // いいねアイコンをtrueに切り替え
                product.likes_count++; // いいね数のインクリメント
            }).catch(error => {
                console.error('商品のお気に入り登録失敗:', error);
            });
        },

        // 商品お気に入り解除
        productUnlike(product) {
            // お気に入り解除APIをPOST送信
            axios.post('/api/user/unlike/' + product.id).then(response => {
                product.liked = false; // いいねアイコンをfalseに切り替え
                product.likes_count--; // いいね数のデクリメント
            }).catch(error => {
                console.error('商品のお気に入り解除失敗:', error);
            });
        },

        // サイドバーに表示するプロフィール情報の取得
        getSidebarProfile() {
            // 利用者側プロフィール情報の取得APIをGET送信
            axios.get('/api/user/mypage/profile').then(response => {
                console.log('APIからのレスポンスデータ:', response.data);
                this.user = response.data.user; // レスポンスデータのユーザー情報をuserプロパティにセット
                // 取得した各プロフィール情報をintroductionプロパティにセット
                this.introduction = this.user.introduction; // 自己紹介文
            }).catch (error => {
                console.error('プロフィール取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },
    },
}
</script>