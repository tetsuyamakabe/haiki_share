<template>
    <main class="l-main">
        <div class="l-main__convenience">
            <h1 class="c-title u-mb__xl">コンビニマイページ</h1>
            <div class="p-article__mypage">
                <section class="p-register u-mr__s">

                    <!-- 出品した商品を最大5件表示 -->
                    <div class="p-mypage__sale u-pd__xl">
                        <h2 class="c-title c-title__sub">出品した商品</h2><span class="c-text c-text__max">最大5件表示</span>
                        <div v-if="saleProducts.length === 0">
                            <ul class="p-product__list">
                                <p class="u-pd__xl">出品した商品はありません。</p>
                            </ul>
                        </div>
                        <div v-else>
                            <ul class="p-product__list">
                                <li v-for="product in saleProducts" :key="product.id" class="p-product__item u-pd__l">
                                    <div class="c-card">
                                        <h3 class="c-card__name u-pdt__s">{{ product.name }}</h3>
                                        <img class="c-card__picture u-m__s" :src="getProductPicturePath(product)" alt="商品画像">
                                        <p class="c-card__price">価格 {{ product.price }} 円</p>
                                        <p class="c-card__date">賞味期限 {{ product.expiration_date }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="c-link__products u-mt__s">
                            <router-link class="c-link" :to="{ name: 'convenience.products.sale' }">全件表示</router-link>
                        </div>
                    </div>

                    <!-- 購入された商品を最大5件表示 -->
                    <div class="p-mypage__purchased u-mt__xl u-pd__xl">
                        <h2 class="c-title c-title__sub">購入された商品</h2><span class="c-text c-text__max">最大5件表示</span>
                        <div v-if="purchaseProducts.length === 0">
                            <ul class="p-product__list">
                                <p class="u-pd__xl">購入された商品はありません。</p>
                            </ul>
                        </div>
                        <div v-else>
                            <ul class="p-product__list">
                                <li v-for="product in purchaseProducts" :key="product.id" class="p-product__item u-pd__l">
                                    <div class="c-card">
                                        <h3 class="c-card__name u-pdt__s">{{ product.name }}</h3>
                                        <img class="c-card__picture u-m__s" :src="getProductPicturePath(product)" alt="商品画像">
                                        <p class="c-card__price">価格 {{ product.price }} 円</p>
                                        <p class="c-card__date">賞味期限 {{ product.expiration_date }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="c-link__products u-mt__s">
                            <router-link class="c-link" :to="{ name: 'convenience.products.purchase' }">全件表示</router-link>
                        </div>
                    </div>
                </section>

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
        </div>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
export default {
    data() {
        return {
            saleProducts: [],
            purchaseProducts: []
        };
    },

    created() {
        this.getMyPageProducts();
    },

    methods: {
        // マイページに表示する出品・購入商品情報の取得
        getMyPageProducts() {
            axios.get('/api/convenience/mypage/products').then(response => {
                this.saleProducts = response.data.sale_products;
                this.purchaseProducts = response.data.purchased_products;
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
    }
};
</script>