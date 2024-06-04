<template>
    <main class="l-main">
        <h1 class="c-title u-mb__xl">商品詳細</h1>
        <div class="p-article">
            <section class="l-main__wrapper u-pd__l">
                <div class="l-container">

                    <h1 class="c-title">{{ product.name }}</h1> <!-- 商品名 -->
                    <div class="p-product">
                        <!-- いいねアイコンとエックスのシェア -->
                        <div class="p-icon">
                            <div class="c-tooltip">
                                <i class="c-icon c-icon__nolike fas fa-heart"></i><span class="u-mr__m">{{ product.likes_count }}</span> <!-- いいね数 -->
                                <div class="c-tooltip__text">ユーザー登録・ログインしてください</div>
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

                <!-- 未ログインユーザーの購入ボタンはユーザー登録する -->
                <button  class="c-button c-button__submit c-button__main u-pd__s" @click="register">ユーザー登録する</button>

            </section>
            <!-- 絞り込み検索フォーム -->
            <search-component />
        </div>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
import axios from 'axios';
import SearchComponent from './SearchComponent.vue'; // 絞り込み検索コンポーネント

export default {
    components: {
        SearchComponent, // 絞り込み検索コンポーネント
    },

    data() {
        return {
            product: [], // 商品情報
            categories: [], // カテゴリ
            convenience: [], // コンビニ情報
            errors: null // エラーメッセージ
        };
    },

    created() {
        this.productId = this.$route.params.productId; // ルートからproductIdを取得
        this.getProduct(); // インスタンス初期化時に商品情報を読み込む
    },

    methods: {
        // 商品情報をサーバーから取得
        getProduct() {
            // 商品情報取得APIをGET送信
            axios.get('/api/products/detail/' + this.productId).then(response => {
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
        Xshare() {
            // エックスの投稿に遷移して商品を不特定多数の人がシェアできるようにする
            const shareURL = 'https://twitter.com/intent/tweet?text=' + "haiki share 商品をシェアする" + "%20%23haikishare" + '&url=' + "https://haikishare.com/user/products/detail/" + this.productId;  
            location.href = shareURL
        },

        // ユーザー登録するボタンの画面遷移先
        register() {
            this.$router.push({ name: 'top' });
        }
    }
}
</script>