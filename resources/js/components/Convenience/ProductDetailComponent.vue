<template>
    <main class="l-main">
        <div class="l-main__convenience">
            <h1 class="c-title u-mb__xl">商品詳細</h1>
                <section class="l-main__wrapper">
                    <div class="l-container">
                        <h1 class="c-title">{{ product.name }}</h1>
                        <div class="p-product">
                            <!-- いいねアイコン -->
                            <div class="p-like p-like__content u-pdr__s">
                                <i v-if="!product.liked" class="c-icon c-icon__unlike far fa-heart" @click="productLike(product)"></i>
                                <i v-else class="c-icon c-icon__like fas fa-heart" @click="productUnlike(product)"></i>
                                <span>いいね{{ product.likes_count }}</span>
                            </div>
                            <!-- エックスのシェアボタン -->
                            <div class="p-like p-like__content">
                                <button class="c-button c-button__share u-pd__s" @click="twitterShare"><i class="fa-brands fa-x-twitter c-icon__share">でシェアする</i></button>
                            </div>

                            <div class="p-product__picture">
                                <!-- 商品画像 -->
                                <img class="c-product__picture--detail" :src="getProductPicturePath(product)" alt="商品画像">
                            </div>
                        </div>
                        <div class="p-product__detail">
                            <p class="c-card__price u-mt__s u-mb__s">価格：{{ product.price }}円</p>
                            <p class="c-card__category u-mt__s u-mb__s">カテゴリ：{{ getCategoryName(product.category_id) }}</p>
                            <p class="c-card__date u-mt__s u-mb__s">賞味期限：{{ formatDate(product.expiration_date) }}</p>
                        </div>
                    </div>

                    <!-- コンビニ側の購入ボタンは自店舗・他店舗に限らず購入ボタンをクリックできないようにする -->
                    <button class="c-button c-button__submit c-button__purchase u-pd__s u-m__s" :disabled="true">コンビニユーザーは購入できません</button>

                </section>
            </div>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            product: [],
            categories: [],
            errors: null
        };
    },

    created() {
        this.productId = this.$route.params.productId; // ルートからproductIdを取得
        this.getProduct(); // 商品情報を取得
    },

    methods: {
        // 商品情報をサーバーから取得
        getProduct() {
            axios.get('/api/products/'+ this.productId).then(response => {
                this.product = response.data.product;
                this.category = response.data.product.category;
                console.log('APIからのレスポンス:', response.data);
                console.log('this.productは、', this.product);
                console.log('this.categoryは、', this.category);
            }).catch(error => {
                console.error('商品情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // カテゴリIDからカテゴリ名を取得するメソッド
        getCategoryName(categoryId) {
            if (this.category && this.category.id === categoryId) {
                return this.category.name;
            }
            return '';
        },

        // 商品画像のパスを取得するメソッド
        getProductPicturePath(product) {
            console.log('productは、', product);
            if (product.pictures && product.pictures.length > 0) {
                return '/storage/product_pictures/' + product.pictures[0].file;
            } else {
                return '/storage/product_pictures/no_image.png';
            }
        },

        // 日付をフォーマットするメソッド
        formatDate(dateString) {
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = ('0' + (date.getMonth() + 1)).slice(-2); // 月は 0 から始まるため +1
            const day = ('0' + date.getDate()).slice(-2);
            return `${year}年${month}月${day}日`;
        },

        // Twitterのシェアボタン
        twitterShare(){
            const shareURL = 'https://twitter.com/intent/tweet?text=' + "haiki share 商品をシェアする" + "%20%23haikishare" + '&url=' + "https://haikishare.com/user/products/detail/" + this.productId;  
            location.href = shareURL
        },

        // 商品お気に入り登録
        productLike(product) {
            axios.post('/api/user/like/' + product.id).then(response => {
                console.log(product.id, 'の商品をお気に入り登録しました。');
                product.liked = true;
                console.log('this.likedは、', product.liked);
                product.likes_count++;
                console.log('product.likes_countは、', product.likes_count);
            }).catch(error => {
                console.error('商品のお気に入り登録失敗:', error);
            });
        },

        // 商品お気に入り解除
        productUnlike(product) {
            axios.post('/api/user/unlike/' + product.id).then(response => {
                console.log(product.id, 'の商品をお気に入り解除しました。');
                product.liked = false;
                console.log('product.likedは、', product.liked);
                product.likes_count--;
                console.log('product.likes_count', product.likes_count);
            }).catch(error => {
                console.error('商品のお気に入り解除失敗:', error);
            });
        },

    },
}
</script>