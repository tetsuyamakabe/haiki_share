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

        <!-- 購入ボタンの条件付き表示 -->
        <div class="p-product__button">
            <button :disabled="!isPurchased" @click="purchaseProduct" class="c-button c-button__purchase">購入する</button>
        </div>
    </div>
</template>

<script>
export default {
    props: ['product', 'categories', 'product_pictures', 'userConvenienceId', 'productConvenienceId'],

    computed: {
        // その商品が他店舗の商品の場合は、購入ボタンをクリックできないようにする
        isPurchased() {
            return this.userConvenienceId === this.productConvenienceId; // ユーザーと紐づくコンビニのuser_idと商品のコンビニidを厳密比較
        }
    },

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

        // 購入するメソッド
        purchaseProduct() {
            console.log('商品を購入しました');
            // 購入ボタンを押すと、購入者・コンビニに通知メールが届くようにすること
            // 購入ボタンを押したら、商品一覧画面に該当商品に「購入済み」ラベルをつけること
            // 「購入済」状態の商品の商品詳細画面では、購入ボタンが押せないこと
            // 自分が購入した商品の商品詳細画面では、「購入をキャンセル」ボタンが表示されること
        }
    },
}
</script>
