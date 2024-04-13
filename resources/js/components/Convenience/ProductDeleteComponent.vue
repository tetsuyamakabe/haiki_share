<template>
    <!-- 商品削除ボタン -->
    <div class="p-product__button">
        <button class="c-button" @click="deleteProduct">商品を削除する</button>
    </div>
</template>

<script>
export default {
    props: ['product'],
    methods: {
        // 商品削除処理をサーバー側に送信するメソッド
        deleteProduct() {
            const productId = this.product.id;
            axios.delete('/convenience/products/' + productId).then(response => {
                console.log('商品が削除されました:', response.data);
                window.location.href = '/convenience/products';
            }).catch(error => {
                console.error('商品削除失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        }
    }
}
</script>