<template>
    <main class="l-main">
        <h1 class="c-title u-mb__xl">コンビニマイページ</h1>
        <div class="p-article">
            <section class="l-main__wrapper">
                <div class="l-container">

                    <!-- 出品した商品を最大5件表示 -->
                    <div class="p-mypage__sale">
                        <h2 class="c-title c-title__sub">出品した商品</h2><span class="c-text c-text__max">最大5件表示</span>
                        <!-- 商品情報がない場合 -->
                        <div v-if="saleProducts.length === 0">
                            <ul class="p-product__list">
                                <p class="c-text u-pd__xl">出品した商品はありません。</p>
                            </ul>
                        </div>
                        <!-- 商品情報を表示 -->
                        <div v-else>
                            <ul class="p-product__list">
                                <li v-for="product in saleProducts" :key="product.id" class="p-product__item">
                                    <div class="c-card u-m__s">
                                        <div class="p-card__header u-pd__s">
                                            <h3 class="c-card__name">{{ product.name }}</h3> <!-- 商品名 -->
                                        </div>
                                        <div class="p-card__container">
                                            <img class="c-card__picture u-mb__s" :src="getProductPicturePath(product)" alt="商品画像"> <!-- 商品画像 -->
                                        </div>
                                        <div class="p-card__footer">
                                            <p class="c-card__price">{{ product.price }}円</p> <!-- 価格 -->
                                            <p class="c-card__date">{{ formatDate(product.expiration_date) }}</p> <!-- 賞味期限日付 -->
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="c-link__products u-mt__s">
                            <router-link class="c-link" :to="{ name: 'convenience.products.sale' }">全件表示</router-link>
                        </div>
                    </div>

                    <!-- 購入された商品を最大5件表示 -->
                    <div class="p-mypage__purchased">
                        <h2 class="c-title c-title__sub">購入された商品</h2><span class="c-text c-text__max">最大5件表示</span>
                        <!-- 商品情報がない場合 -->
                        <div v-if="purchaseProducts.length === 0">
                            <ul class="p-product__list">
                                <p class="c-text u-pd__xl">購入された商品はありません。</p>
                            </ul>
                        </div>
                        <!-- 商品情報を表示 -->
                        <div v-else>
                            <ul class="p-product__list">
                                <li v-for="product in purchaseProducts" :key="product.id" class="p-product__item">
                                    <div class="c-card u-m__s">
                                        <div class="p-card__header u-pd__s">
                                            <h3 class="c-card__name">{{ product.name }}</h3> <!-- 商品名 -->
                                        </div>
                                        <div class="p-card__container">
                                            <img class="c-card__picture u-mb__s" :src="getProductPicturePath(product)" alt="商品画像"> <!-- 商品画像 -->
                                        </div>
                                        <div class="p-card__footer">
                                            <p class="c-card__price">{{ product.price }}円</p> <!-- 価格 -->
                                            <p class="c-card__date">{{ formatDate(product.expiration_date) }}</p> <!-- 賞味期限日付 -->
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="c-link__products u-mt__s">
                            <router-link class="c-link" :to="{ name: 'convenience.products.purchase' }">全件表示</router-link>
                        </div>
                    </div>

                </div>
            </section>

            <!-- サイドバー -->
            <section class="l-sidebar">
                <div class="p-mypage__sidebar">
                    <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'convenience.profile' }">プロフィール編集</router-link>
                    <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'convenience.withdraw' }">退会</router-link>
                    <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'convenience.products.create' }">商品出品</router-link>
                    <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'convenience.products.sale' }">出品した商品一覧</router-link>
                    <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'convenience.products.purchase' }">購入された商品一覧</router-link>
                    <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'products' }">商品一覧</router-link>
                </div>
            </section>
        </div>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
export default {
    data() {
        return {
            saleProducts: [], // 出品した商品情報
            purchaseProducts: [] // 購入された商品情報
        };
    },

    computed: {
        // ログインユーザーかどうか
        isLogin() {
            return this.$store.getters['auth/check'];
        },
    },

    created() {
        this.getMyPageProducts(); // インスタンス初期化時にマイページに表示する出品・購入商品情報を読み込む
    },

    methods: {
        // マイページに表示する出品・購入商品情報の取得
        getMyPageProducts() {
            // コンビニマイページに表示する出品・購入商品情報の取得APIをGET送信
            axios.get('/api/convenience/mypage/products').then(response => {
                // レスポンスデータをそれぞれのプロパティにセット
                this.saleProducts = response.data.sale_products; // 出品した商品情報
                this.purchaseProducts = response.data.purchased_products; // 購入された商品情報
            }).catch(error => {
                console.error('商品情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // 商品画像のパスを取得するメソッド
        getProductPicturePath(product) {
            if (product.pictures.length > 0) {
                return 'https://haikishare.com/product_pictures/' + product.pictures[0].file; // 商品画像がある場合は、その画像パスを返す
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
    }
};
</script>