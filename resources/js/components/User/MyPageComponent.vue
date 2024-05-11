<template>
    <main class="l-main">
        <div class="l-main__user">
            <h1 class="c-title u-mb__xl"><span>利用者マイページ</span></h1>
            <div class="p-article__mypage">
                <section class="p-register u-mr__s">

                    <!-- 購入した商品を最大5件表示 -->
                    <div class="p-mypage__purchased u-pd__xl">
                        <h2 class="c-title c-title__sub">購入した商品</h2><span class="c-text c-text__max">最大5件表示</span>
                        <div v-if="purchasedProducts.length === 0">
                            <ul class="p-product__list">
                                <p class="u-pd__xl">購入した商品はありません。</p>
                            </ul>
                        </div>
                        <div v-else>
                            <ul class="p-product__list">
                                <li v-for="product in purchasedProducts" :key="product.id" class="p-product__item">
                                    <div class="c-card u-pdb__s">
                                        <h3 class="c-card__name u-pdt__s">{{ product.name }}</h3>
                                        <img class="c-card__picture u-m__s" :src="getProductPicturePath(product)" alt="商品画像">
                                        <p class="c-card__price">価格 {{ product.price }} 円</p>
                                        <p class="c-card__date">賞味期限 {{ product.expiration_date }}</p>
                                        <div class="p-product__button">
                                            <router-link :to="getProductDetailLink(product.id)" class="c-button">詳細を見る</router-link>
                                        </div>
                                        <div class="p-product__button">
                                            <button v-if="product.is_purchased === true && product.purchased_id === loginId" class="c-button c-button__cancel" @click="cancelPurchase">購入をキャンセルする</button>
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
                    <div class="p-mypage__liked u-mt__xl u-pd__xl">
                        <h2 class="c-title c-title__sub">お気に入り商品</h2><span class="c-text c-text__max">最大5件表示</span>
                        <div v-if="likedProducts.length === 0">
                            <ul class="p-product__list">
                                <p class="u-pd__xl">お気に入り登録した商品はありません。</p>
                            </ul>
                        </div>
                        <div v-else>
                            <ul class="p-product__list">
                                <li v-for="product in likedProducts" :key="product.id" class="p-product__item">
                                    <div class="c-card u-pdb__s">
                                        <h3 class="c-card__name u-pdt__s">{{ product.name }}</h3>
                                        <img class="c-card__picture u-m__s" :src="getProductPicturePath(product)" alt="商品画像">
                                        <p class="c-card__price">価格 {{ product.price }} 円</p>
                                        <p class="c-card__date">賞味期限 {{ product.expiration_date }}</p>
                                        <router-link :to="getProductDetailLink(product.id)" class="c-button c-button__user c-button__detail">詳細を見る</router-link>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="c-link__products u-mt__s">
                            <router-link class="c-link" :to="{ name: 'user.products.liked' }">全件表示</router-link>
                        </div>
                    </div>
                </section>

                <section class="l-sidebar">
                    <div class="p-mypage__sidebar">
                        <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'user.profile' }">プロフィール編集</router-link>
                        <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'user.withdraw' }">退会</router-link>
                        <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'products' }">商品一覧</router-link>
                    </div>
                </section>
            </div>
        </div>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
export default {
    data() {
        return {
            purchasedProducts: [],
            likedProducts: [],
        };
    },

    created() {
        this.getMyPageProducts(); // サーバから商品情報を取得
    },

    methods: {
        // マイページに表示する購入・お気に入り商品情報の取得
        getMyPageProducts() {
            console.log('購入した商品情報を取得します');
            axios.get('/api/user/mypage/products').then(response => {
                this.purchasedProducts = response.data.purchased_products;
                this.likedProducts = response.data.liked_products;
                console.log('購入した商品情報:', this.purchasedProducts);
                console.log('お気に入り商品情報:', this.likedProducts);
                console.log('APIからのレスポンス:', response.data);
                this.lastPage = response.data.products.last_page;
            }).catch(error => {
                console.error('商品情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // 商品画像のパスを取得するメソッド
        getProductPicturePath(product) {
            console.log('productは、', product);
            if (product.product.pictures.length > 0) {
                return '/storage/product_pictures/' + product.product.pictures[0].file;
            } else {
                return '/storage/product_pictures/no_image.png';
            }
        },

        // 商品詳細画面のリンクを返すメソッド
        getProductDetailLink(productId) {
            return { name: 'user.products.detail', params: { productId: productId } };
        },
    },
}
</script>