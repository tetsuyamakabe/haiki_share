<template>
    <main class="l-main">
        <div class="p-mypage">
            <div class="p-mypage__main">
                <h1 class="c-title">マイページ</h1>
                <div class="p-container">
                    <router-link :to="{ name: 'user.profile' }">プロフィール編集</router-link>
                    <router-link :to="{ name: 'user.withdraw' }">退会</router-link>
                    <router-link :to="{ name: 'products' }">商品一覧</router-link>
                </div>
            </div>
            <!-- 購入した商品を最大5件表示 -->
            <div>
                <h2>購入した商品</h2>
                <ul class="p-product__list">
                    <li v-for="product in purchasedProducts" :key="product.id" class="p-product__item">
                        <h3 class="c-product__name">{{ product.name }}</h3>
                        <div class="p-product__picture--container">
                            <img class="c-product__picture" :src="getProductPicturePath(product)" alt="Product Image">
                        </div>
                        <p class="c-product__price">価格 {{ product.price }} 円</p>
                        <p class="c-product__date">賞味期限 {{ product.expiration_date }}</p>
                        <div class="p-product__button">
                            <router-link :to="getProductDetailLink(product.id)" class="c-button">詳細を見る</router-link>
                        </div>
                        <div class="p-product__button">
                            <button v-if="product.is_purchased === true && product.purchased_id === loginId" class="c-button c-button__cancel" @click="cancelPurchase">購入をキャンセルする</button>
                        </div>
                    </li>
                </ul>
                <router-link :to="{ name: 'user.products.purchased' }">全件表示</router-link>
            </div>

            <!-- お気に入りした商品を最大5件表示 -->
            <div>
                <h2>お気に入り商品</h2>
                <ul class="p-product__list">
                    <li v-for="product in likedProducts" :key="product.id" class="p-product__item">
                        <h3 class="c-product__name">{{ product.name }}</h3>
                        <div class="p-product__picture--container">
                            <img class="c-product__picture" :src="getProductPicturePath(product)" alt="Product Image">
                        </div>
                        <p class="c-product__price">価格 {{ product.price }} 円</p>
                        <p class="c-product__date">賞味期限 {{ product.expiration_date }}</p>
                        <div class="p-product__button">
                            <router-link :to="getProductDetailLink(product.id)" class="c-button">詳細を見る</router-link>
                        </div>
                    </li>
                </ul>
                <router-link :to="{ name: 'user.products.liked' }">全件表示</router-link>
            </div>
        </div>
        <a @click="$router.back()">前のページに戻る</a>
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