<template>
    <main class="l-main">
        <div class="l-main__header">
            <h1 class="c-title">購入された商品一覧</h1>
        </div>
        <div class="l-article">
            <div class="l-article__main">
                <div class="p-product">
                    <!-- 全体件数と1ページの表示件数を表示 -->
                    <p class="c-text">{{ products.data ? products.data.length : 0 }}件表示 / 全{{ products.total ? products.total : 0 }}件中</p>
                    <ul class="p-product--list">
                        <!-- 購入された商品がない場合 -->
                        <li v-if="!products.data || products.data.length === 0" class="p-product__item">
                            <p class="c-text u-m__xl">購入された商品はありません。</p>
                        </li>
                        <!-- 購入された商品一覧を表示 -->
                        <li v-else v-for="product in products.data" :key="product.id" class="p-product__item">
                            <!-- 商品情報の表示 -->
                            <div class="c-card">
                                <div class="c-card__header">
                                    <h3 class="c-card__name">{{ product.name }}</h3> <!-- 商品名 -->
                                </div>
                                <div class="c-card__body">
                                    <img class="c-card__img" :src="getProductPicturePath(product)" alt="商品画像"> <!-- 商品画像 -->
                                    <div class="c-icon">
                                        <!-- コンビニユーザーの場合にコンビニユーザーツールチップを表示 -->
                                        <div v-if="$store.getters['auth/check'] && $store.getters['auth/role'] === 'convenience'">
                                            <div class="c-tooltip">
                                                <i class="c-icon c-icon--notlike far fa-heart"></i>{{ product.likes_count }} <!-- いいね数 -->
                                                <div class="c-tooltip__message">コンビニユーザーはお気に入り登録できません</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 賞味期限までの残り日数を表示 -->
                                    <p class="c-card__text">
                                        <i class="fa-regular fa-clock"></i>
                                        <span v-if="getExpirationDate(product.expiration_date) >= 0">残り{{ getExpirationDate(product.expiration_date) }}日</span>
                                        <span v-if="getExpirationDate(product.expiration_date) < 0">賞味期限切れ</span>
                                    </p>
                                    <p class="c-card__text"><i class="fa-solid fa-calendar-days"></i>{{ formatDate(product.expiration_date) }}</p> <!-- 賞味期限日付 -->
                                    <p class="c-card__label c-card__category">{{ product.category.name }}</p> <!-- カテゴリー名 -->
                                    <p class="c-card__label c-card__price"><i class="fa-solid fa-yen-sign"></i>{{ product.price }}</p> <!-- 価格 -->
                                </div>
                                <div class="c-card__footer">
                                    <router-link :to="getProductDetailLink(product.id)" class="c-button c-button--default">詳細を見る</router-link>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- サイドバー -->
            <sidebar-component :convenience_name="convenience_name" :branch_name="branch_name" :prefecture="prefecture" :city="city" :town="town" :building="building" :introduction="introduction"></sidebar-component>
        </div>
        <!-- ページネーション -->
        <pagination @onClick="onPageChange" :current_page="currentPage" :last_page="lastPage" />
    </main>
</template>

<script>
import SidebarComponent from './SidebarComponent.vue'; // サイドバーコンポーネント
import Pagination from '../Parts/Pagination.vue'; // ページネーションコンポーネント

export default {
    components: {
        SidebarComponent, // サイドバーコンポーネントを読み込み
        Pagination, // ページネーションコンポーネントを読み込み
    },

    data() {
        return {
            products: [], // 商品情報
            currentPage: 1, // 現在ページ
            lastPage: 1, // 最後のページ
            convenience_name: '', // コンビニ名
            branch_name: '', // 支店名
            prefecture: '', // 都道府県
            city: '', // 市区町村
            town: '', // 地名・番地
            building: '', // 建物名・部屋番号
            introduction: '', // 自己紹介文
        };
    },

    computed: {
        // ログインユーザーかどうか
        isLogin() {
            return this.$store.getters['auth/check'];
        },
    },

    created() {
        this.getPurchasedProduct(); // インスタンス初期化時に購入された商品情報を読み込む
        this.getSidebarProfile(); // インスタンス初期化時にサイドバーに表示するプロフィール情報を読み込む
    },

    methods: {
        // URLを作成する
        createURL(params) {
            // URLの組み立て
            let url = `/convenience/products/purchased`;
            if (params && params.page) { // パラメータとパラメータのpageがある場合
                url += `?page=${params.page}`; // urlにparams.pageを追加
            } else {
                url += `?page=${this.currentPage}`; // urlにthis.currentPageを追加
            }
            // ページ遷移
            this.$router.push(url).then(() => {
                this.getPurchasedProduct(); // ページ遷移が完了した後にgetPurchasedProduct()メソッドを呼び出す
            });
        },

        // ページが変更されたときの処理
        onPageChange(page) {
            if (this.currentPage !== page) { // 現在のページ番号と新しいページ番号が異なるか
                this.currentPage = page; // ページ番号を更新
                const params = Object.assign({}, this.$route.query); // 新しいクエリパラメータをparamsオブジェクトにコピー
                params.page = page; // 新しいページ番号にする
                this.createURL(params); // 新しいURLを生成して画面遷移
            }
        },

        // 購入された商品情報をサーバーから取得
        getPurchasedProduct() {
            // 現在のルートのクエリパラメータを取得
            const params = Object.assign({}, this.$route.query); // クエリパラメータのコピーを作成
            // 購入された商品情報取得APIをGET送信
            axios.get('/api/convenience/products/purchased', { params: params }).then(response => { // パラメータを含むリクエスト
                // レスポンスデータをそれぞれのプロパティにセット
                this.products = response.data.products; // 購入された商品情報
                this.lastPage = response.data.products.last_page; // ページ数
            }).catch(error => {
                this.errors = error.response.data;
            });
        },

        // 商品画像のパスを取得するメソッド
        getProductPicturePath(product) {
            if (product.pictures.length > 0) {
                return product.pictures[0].file; // 商品画像がある場合は、その画像パスを返す
            } else {
                return 'https://haikishare.com/product_pictures/no_image.png'; // 商品画像がない場合は、デフォルトの商品画像のパスを返す
            }
        },

        // カテゴリー名を取得するメソッド
        getCategoryName(category) {
            return category && category.name ? category.name : 'その他';
        },

        // 賞味期限日付をフォーマットするメソッド
        formatDate(dateString) {
            const date = new Date(dateString); // Dateオブジェクトに変換する
            const year = date.getFullYear(); // 年数を取得
            const month = ('0' + (date.getMonth() + 1)).slice(-2); // 月数を取得、1桁の場合は2桁の数値に変換
            const day = ('0' + date.getDate()).slice(-2); // 日数を取得、1桁の場合は2桁の数値に変換
            return `${year}年${month}月${day}日`; // 年月日のフォーマットされた賞味期限日付を返す
        },

        // 賞味期限までの残り日数を計算するメソッド
        getExpirationDate(expirationDate) {
            const today = new Date(); // 今日の日付を取得
            const expiry = new Date(expirationDate); // 賞味期限の日付を取得
            const difference = expiry.getTime() - today.getTime(); // 残り日数をミリ秒で計算
            const daysRemaining = Math.ceil(difference / (1000 * 60 * 60 * 24)); // ミリ秒を日数に変換して切り上げ
            return `${daysRemaining}`; // 残り日数を表示する文字列を返す
        },

        // 商品詳細画面のリンクを返すメソッド
        getProductDetailLink(productId) {
            return { name: 'convenience.products.detail', params: { productId: productId } }; // 商品詳細画面のリンクを返す
        },

        // サイドバーに表示するプロフィール情報の取得
        getSidebarProfile() {
            // コンビニ側プロフィール情報の取得APIをGET送信
            axios.get('/api/convenience/mypage/profile').then(response => {
                // レスポンスデータをそれぞれのプロパティにセット
                this.user = response.data.user; // ユーザー情報
                this.convenience = response.data.convenience; // コンビニ情報
                this.address = response.data.address; // 住所情報
                // 取得した各プロフィール情報をそれぞれのプロパティにセット
                this.convenience_name = this.user.name; // コンビニ名
                this.branch_name = this.convenience.branch_name; // 支店名
                this.prefecture = this.address.prefecture; // 住所
                this.city = this.address.city; // 市区町村
                this.town = this.address.town; // 地名・番地
                this.building = this.address.building; // 建物名・部屋番号
                this.introduction = this.user.introduction; // 自己紹介文
            }).catch(error => {
                this.errors = error.response.data;
            });
        }
    },
}
</script>