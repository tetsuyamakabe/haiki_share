<template>
    <!-- 商品削除ボタン -->
    <div class="p-product__button">
        <button class="c-button" @click="deleteProduct">商品を削除する</button>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    created() {
        this.productId = this.$route.params.productId; // ルートからproductIdを取得
    },

    methods: {
        // 商品削除処理をサーバー側に送信するメソッド
        deleteProduct() {
            const productId = this.productId;
            axios.delete('/api/convenience/products/' + productId).then(response => {
                console.log('商品情報を削除します。');
                this.$router.push({ name: 'convenience.products.sale' }); // 商品削除処理後、出品した商品一覧画面に遷移
            }).catch(error => {
                console.error('商品削除失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        }
    }
}
</script>