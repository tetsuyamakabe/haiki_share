<template>
    <main class="l-main">
        <div class="p-mypage">
            <div class="p-mypage__main">
                <h1 class="c-title">マイページ</h1>
                <div class="p-container">
                    <router-link :to="{ name: 'convenience.profile' }">プロフィール編集</router-link>
                    <router-link :to="{ name: 'convenience.withdraw' }">退会</router-link>
                    <router-link :to="{ name: 'convenience.products.create' }">商品出品</router-link>
                    <router-link :to="{ name: 'convenience.products.sale' }">出品した商品一覧</router-link>
                    <router-link :to="{ name: 'convenience.products.purchase' }">購入された商品一覧</router-link>
                    <router-link :to="{ name: 'products' }">商品一覧</router-link>
                </div>
                <!-- 出品した商品を最大5件表示 -->
                <div>
                    <h2>出品した商品</h2>
                    <ul class="p-product__list">
                        <li v-for="product in saleProducts" :key="product.id" class="p-product__item">
                            <h3 class="c-product__name">{{ product.name }}</h3>
                            <div class="p-product__picture--container">
                                <img class="c-product__picture" :src="getProductPicturePath(product)" alt="Product Image">
                            </div>
                            <p class="c-product__price">価格 {{ product.price }} 円</p>
                            <p class="c-product__date">賞味期限 {{ product.expiration_date }}</p>
                        </li>
                    </ul>
                    <router-link :to="{ name: 'convenience.products.sale' }">全件表示</router-link>
                </div>

                <!-- 購入された商品を最大5件表示 -->
                <div>
                    <h2>購入された商品</h2>
                    <ul class="p-product__list">
                        <li v-for="product in purchaseProducts" :key="product.id" class="p-product__item">
                            <h3 class="c-product__name">{{ product.name }}</h3>
                            <div class="p-product__picture--container">
                                <img class="c-product__picture" :src="getProductPicturePath(product)" alt="Product Image">
                            </div>
                            <p class="c-product__price">価格 {{ product.price }} 円</p>
                            <p class="c-product__date">賞味期限 {{ product.expiration_date }}</p>
                        </li>
                    </ul>
                    <router-link :to="{ name: 'convenience.products.purchase' }">全件表示</router-link>
                </div>
            </div>
        </div>
        <a @click="$router.back()">前のページに戻る</a>
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