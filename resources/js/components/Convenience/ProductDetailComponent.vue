<template>
    <div class="p-container">
        <table>
            <h1>{{ product.name }}</h1>
            <tr>
                <th><label for="price" class="c-label">価格</label></th>
                <td>{{ product.price }}</td>
            </tr>

            <tr>
                <th><label for="category" class="c-label">カテゴリ名</label></th>
                <td>{{ getCategoryName(product.category_id) }}</td>
            </tr>

            <tr>
                <th><label for="expiration_date" class="c-label">賞味期限</label></th>
                <td>{{ product.expiration_date }}</td>
            </tr>

            <tr>
                <!-- 【TODO】 商品画像を複数枚あっても表示できるように画像スライダーを使う -->
                <th><label for="product_picture" class="c-label">商品画像</label></th>
                <td><img :src="product_pictures[0].url" alt="商品画像" class="c-product__picture"></td>
            </tr>
        </table>

        <!-- コンビニ側の購入ボタンは自店舗・他店舗に限らず購入ボタンをクリックできないようにする -->
        <div class="p-product__button">
            <button class="c-button c-button__purchase" :disabled="true">購入する</button>
        </div>
    </div>
</template>

<script>
export default {
    props: ['product', 'categories', 'product_pictures'],
    methods: {
        // カテゴリIDからカテゴリ名を取得するメソッド
        getCategoryName(categoryId) {
            for (let i = 0; i < this.categories.length; i++) {
                if (this.categories[i].id === categoryId) {
                    return this.categories[i].name;
                }
            }
            return '';
        },
    },
}
</script>