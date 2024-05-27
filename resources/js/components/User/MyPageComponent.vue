<template>
    <main class="l-main">
        <h1 class="c-title u-mb__xl">利用者マイページ</h1>
        <div class="p-article">
            <section class="l-main__wrapper">
                <div class="l-container">

                    <!-- 購入した商品を最大5件表示 -->
                    <div class="p-mypage__purchased">
                        <h2 class="c-title c-title__sub">購入した商品</h2><span class="c-text c-text__max">最大5件表示</span>
                        <!-- 商品情報がない場合 -->
                        <div v-if="purchasedProducts.length === 0">
                            <ul class="p-product__list">
                                <p class="c-text u-pd__xl">購入した商品はありません。</p>
                            </ul>
                        </div>
                        <!-- 商品情報を表示 -->
                        <div v-else>
                            <ul class="p-product__list">
                                <li v-for="product in purchasedProducts" :key="product.id" class="p-product__item">
                                    <div class="c-card u-m__s">
                                        <div class="p-card__header u-pd__s">
                                            <h3 class="c-card__name">{{ product.product.name }}</h3> <!-- 商品名 -->
                                        </div>
                                        <div class="p-card__container">
                                            <img class="c-card__picture" :src="getProductPicturePath(product)" alt="商品画像"> <!-- 商品画像 -->
                                            <p class="c-card__price">{{ product.product.price }}円</p> <!-- 価格 -->
                                            <p class="c-card__date">{{ formatDate(product.product.expiration_date) }}</p> <!-- 賞味期限日付 -->
                                        </div>
                                        <div class="p-card__footer">
                                            <div class="p-product__button">
                                                <router-link :to="getProductDetailLink(product.product.id)" class="c-button c-button__user c-button__detail u-pd__s u-m__s">詳細を見る</router-link>
                                                <button class="c-button c-button__user c-button__cancel u-pd__s u-m__s" @click="cancelPurchase(product.product.id)">購入をキャンセルする</button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="c-link__products u-mt__s">
                            <router-link class="c-link" :to="{ name: 'user.products.purchased' }">全件表示</router-link>
                        </div>
                    </div>

                    <!-- お気に入りした商品を最大5件表示 -->
                    <div class="p-mypage__liked">
                        <h2 class="c-title c-title__sub">お気に入り商品</h2><span class="c-text c-text__max">最大5件表示</span>
                        <div v-if="likedProducts.length === 0">
                            <ul class="p-product__list">
                                <p class="c-text u-pd__xl">お気に入り登録した商品はありません。</p>
                            </ul>
                        </div>
                        <!-- 商品情報を表示 -->
                        <div v-else>
                            <ul class="p-product__list">
                                <li v-for="product in likedProducts" :key="product.id" class="p-product__item">
                                    <div class="c-card u-m__s">
                                        <div class="p-card__header u-pd__s">
                                            <h3 class="c-card__name">{{ product.product.name }}</h3> <!-- 商品名 -->
                                        </div>
                                        <div class="p-card__container">
                                            <img class="c-card__picture" :src="getProductPicturePath(product)" alt="商品画像"> <!-- 商品画像 -->
                                            <p class="c-card__price">{{ product.product.price }}円</p> <!-- 価格 -->
                                            <p class="c-card__date">{{ formatDate(product.product.expiration_date) }}</p> <!-- 賞味期限日付 -->
                                        </div>
                                        <div class="p-card__footer">
                                            <router-link :to="getProductDetailLink(product.product.id)" class="c-button c-button__user c-button__detail">詳細を見る</router-link>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="c-link__products u-mt__s">
                            <router-link class="c-link" :to="{ name: 'user.products.liked' }">全件表示</router-link>
                        </div>
                    </div>

                </div>
            </section>

            <!-- サイドバー -->
            <section class="l-sidebar">
                <div class="p-mypage__sidebar">
                    <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'user.profile' }">プロフィール編集</router-link>
                    <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'user.withdraw' }">退会</router-link>
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
            purchasedProducts: [], // 購入された商品情報
            likedProducts: [], // お気に入り登録した商品情報
        };
    },

    created() {
        this.getMyPageProducts(); // インスタンス初期化時に商品情報を読み込む
    },

    methods: {
        // マイページに表示する購入・お気に入り商品情報の取得
        async getMyPageProducts() {
            try {
                // コンビニマイページに表示する出品・購入商品情報の取得APIをGET送信
                await axios.get('/api/user/mypage/products');
                // レスポンスデータをそれぞれのプロパティにセット
                this.purchasedProducts = response.data.purchased_products;
                this.likedProducts = response.data.liked_products;
            } catch (error) {
                console.error('商品情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            }
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
            return { name: 'user.products.detail', params: { productId: productId } }; // 商品詳細画面のリンクを返す
        },

        // 商品購入キャンセルするメソッド
        async cancelPurchase(productId) {
            try {
                // 購入キャンセルAPIをDELETE送信
                await axios.delete(`/api/user/products/purchase/cancel/${productId}`);
                console.log('APIからのレスポンス:', response.data);
                this.getMyPageProducts(); // 購入した商品のリストを再取得する
            } catch (error) {
                console.error('商品購入キャンセル処理失敗:', error.response.data);
                this.errors = error.response.data.errors;
            }
        },
    },
}
</script>