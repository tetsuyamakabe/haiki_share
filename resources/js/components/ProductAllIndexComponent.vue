<template>
    <div class="p-container">
        <ul class="p-product__list">
            <li v-for="product in products" :key="product.id" class="p-product__item">
                <h3 class="c-product__name">{{ product.name }}</h3>
                <div class="p-product__picture--container">
                    <img class="c-product__picture" :src="getProductPicturePath(product)" alt="Product Image">
                </div>
                <p class="c-product__price">価格 {{ product.price }} 円</p>
                <p class="c-product__date">賞味期限 {{ product.expiration_date }}</p>
                <div class="p-product__button">
                    <!-- 商品詳細ページのリンク -->
                    <a :href="getProductDetailLink(product.id)" class="c-button">詳細を見る</a>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ['products', 'productDetailLink'],
    methods: {
        // 商品画像のパスを取得するメソッド
        getProductPicturePath(product) {
            if (product.pictures.length > 0) {
                return '/storage/product_pictures/' + product.pictures[0].file;
            } else {
                return '/storage/product_pictures/no_image.png';
            }
        },

        // 商品詳細ページへのリンクを取得するメソッド
        getProductDetailLink(productId) {
            // Laravelから受け取ったproductDetailLinkを使って各商品の詳細画面のリンクを取得する
            let detailLink = this.productDetailLink[productId];
            console.log('detailLinkは、', detailLink);
            return detailLink;
        }
    }
}
</script>
