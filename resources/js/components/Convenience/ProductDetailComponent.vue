<template>
    <main class="l-main">
        <h1 class="c-title u-mb__xl">商品詳細</h1>
        <div class="p-article">
            <section class="l-main__wrapper u-pd__l">
                <div class="l-container">

                    <h1 class="c-title u-mt__xl u-mb__xl">{{ product.name }}</h1> <!-- 商品名 -->
                    <div class="p-product">
                        <!-- いいねアイコンとエックスのシェア -->
                        <div class="p-icon">
                            <div class="c-tooltip">
                                <i class="c-icon c-icon__nolike fas fa-heart"></i><span class="u-mr__m">{{ product.likes_count }}</span> <!-- いいね数 -->
                                <div class="c-tooltip__text">コンビニユーザーはお気に入りできません</div>
                            </div>
                            <i class="fa-brands fa-x-twitter c-icon c-icon__share u-pd__s" @click="Xshare"><span class="c-text">でシェア</span></i>
                        </div>
                        <!-- 商品画像 -->
                        <div class="p-product__picture">
                            <img class="c-product__picture--detail" :src="getProductPicturePath(product)" alt="商品画像">
                        </div>
                    </div>
                    <!-- 商品情報 -->
                    <div class="p-product__detail" v-if="product && product.convenience && product.convenience.user">
                        <p class="c-card__text u-mt__s u-mb__s">価格：{{ product.price }}円</p>
                        <p class="c-card__text u-mt__s u-mb__s">カテゴリ：{{ getCategoryName(product.category_id) }}</p>
                        <p class="c-card__text u-mt__s u-mb__s">賞味期限：{{ formatDate(product.expiration_date) }}</p>
                        <p class="c-card__text u-mt__s u-mb__s">この商品を出品したコンビニ：{{ product.convenience.user.name }} {{ product.convenience.branch_name }}</p>
                        <p class="c-card__text u-mt__s u-mb__s">住所：{{ product.convenience.address.prefecture }}{{ product.convenience.address.city }}{{ product.convenience.address.town }}{{ product.convenience.address.building }}</p>
                    </div>
                </div>

                <!-- コンビニ側の購入ボタンは自店舗・他店舗に限らず購入ボタンをクリックできないようにする -->
                <button class="c-button c-button__submit c-button__purchase" :disabled="true">コンビニユーザーは購入できません</button>

            </section>
            <!-- サイドバー -->
            <sidebar-component :convenience_name="convenience_name" :branch_name="branch_name" :prefecture="prefecture" :city="city" :town="town" :building="building" :introduction="introduction"></sidebar-component>
        </div>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
import axios from 'axios';
import SidebarComponent from './SidebarComponent.vue';

export default {
    components: {
        SidebarComponent // サイドバーコンポーネントを読み込み
    },

    data() {
        return {
            product: [], // 商品情報
            categories: [], // カテゴリ
            convenience: [], // コンビニ情報
            convenience_name: '', // コンビニ名
            branch_name: '', // 支店名
            prefecture: '', // 都道府県
            city: '', // 市区町村
            town: '', // 地名・番地
            building: '', // 建物名・部屋番号
            introduction: '', // 自己紹介文
            errors: null // エラーメッセージ
        };
    },

    computed: {
        // ログインユーザーかどうか
        isLogin() {
            return this.$store.getters['auth/check'];
        },
    },

    created() {
        this.productId = this.$route.params.productId; // ルートからproductIdを取得
        this.getProduct(); // インスタンス初期化時に商品情報を読み込む
        this.getSidebarProfile(); // インスタンス初期化時にサイドバーに表示するプロフィール情報を読み込む
    },

    methods: {
        // 商品情報をサーバーから取得
        getProduct() {
            // 商品情報取得APIをGET送信
            axios.get('/api/products/' + this.productId).then(response => {
                // レスポンスデータをそれぞれのプロパティにセット
                this.product = response.data.product; // 商品情報
                this.category = response.data.product.category; // カテゴリ情報
                this.convenience = response.data.product.convenience; // コンビニ情報
            }).catch(error => {
                console.error('商品情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // カテゴリIDからカテゴリ名を取得するメソッド
        getCategoryName(categoryId) {
            if (this.category && this.category.id === categoryId) { // カテゴリ情報がある場合はcategoryIdと一致するidがあるか
                return this.category.name; // カテゴリ名を返す
            }
            return ''; // ない場合は空文字を返す
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

        // エックスのシェアボタン
        Xshare(){
            // エックスの投稿に遷移して商品を不特定多数の人がシェアできるようにする
            const shareURL = 'https://twitter.com/intent/tweet?text=' + "haiki share 商品をシェアする" + "%20%23haikishare" + '&url=' + "https://haikishare.com/user/products/detail/" + this.productId;  
            location.href = shareURL
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
            })
            .catch(error => {
                console.error('プロフィール取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        }
    },
}
</script>