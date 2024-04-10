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
                    <button class="c-button">詳細を見る</button>
                    <button class="c-button">編集する</button>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ['products'],
    methods: {
        getProductPicturePath(product) {
            // 商品の画像パスを取得する
            if (product.pictures.length > 0) {
                return '/storage/product_pictures/' + product.pictures[0].file;
            } else {
                // 画像がない場合はデフォルトの画像パスを返す
                return '/storage/product_pictures/no_image.png';
            }
        },
    }
}
</script>
