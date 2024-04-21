<template>
    <div class="p-container">
        <ul class="p-product__list">
            <li v-for="product in products.data" :key="product.id" class="p-product__item">
                <h3 class="c-product__name">{{ product.name }}</h3>
                <div class="p-product__picture--container">
                    <img class="c-product__picture" :src="getProductPicturePath(product)" alt="Product Image">
                </div>
                <p class="c-product__price">価格 {{ product.price }} 円</p>
                <p class="c-product__date">賞味期限 {{ product.expiration_date }}</p>
                <div class="p-product__button">
                    <a :href="getProductDetailLink(product.id)" class="c-button">詳細を見る</a>
                </div>
            </li>
        </ul>

        <!-- ページネーション -->
        <ul class="pagination">
            <!-- 前に移動する<<ボタン -->
            <li :class="{ 'inactive': current_page == 1, 'disabled': current_page == 1 }" @click="changePage(current_page - 1)">«</li>
            <!-- ページ番号の範囲 -->
            <li v-for="page in frontPageRange" :key="page" @click="changePage(page)" :class="{ 'active': isCurrent(page), 'inactive': !isCurrent(page) }">{{ page }}</li>
            <!-- ドットの表示 -->
            <li v-if="front_dot" class="inactive disabled">...</li>
            <!-- ページ番号の範囲 -->
            <li v-for="page in middlePageRange" :key="page" @click="changePage(page)" :class="{ 'active': isCurrent(page), 'inactive': !isCurrent(page) }">{{ page }}</li>
            <!-- ドットの表示 -->
            <li v-if="end_dot" class="inactive">...</li>
            <!-- 次に移動する>>ボタン -->
            <li :class="{ 'inactive': current_page >= last_page, 'disabled': current_page >= last_page }" @click="changePage(current_page + 1)">»</li>
        </ul>
    </div>
</template>


<script>
import axios from 'axios';

export default {
    props: ['products', 'productDetailLink'],

    data() {
        return {
            localProduct: [],
            current_page: 1, // 現在のページ番号
            last_page: '', // 最後のページ番号
            range: 5, // 表示されるページの範囲
            front_dot: false, // 前のページにドットを表示させるか
            end_dot: false // 後ろのページにドットを表示させるか
        };
    },

    computed: {
        // 最初のページ番号の範囲
        frontPageRange() {
            if (!this.sizeCheck) {
                this.front_dot = false;
                this.end_dot = false;
                return this.calRange(1, this.last_page);
            }
            return this.calRange(1, 2);
        },

        // 中間のページ番号の範囲
        middlePageRange() {
            if (!this.sizeCheck) return [];
            let start = "";
            let end = "";
            if (this.current_page <= this.range) {
                start = 3;
                end = this.range + 2;
                this.front_dot = false;
                this.end_dot = true;
            } else if (this.current_page > this.last_page - this.range) {
                start = this.last_page - this.range - 1;
                end = this.last_page - 2;
                this.front_dot = true;
                this.end_dot = false;
            } else {
                start = this.current_page - Math.floor(this.range / 2);
                end = this.current_page + Math.floor(this.range / 2);
                this.front_dot = true;
                this.end_dot = true;
            }
            return this.calRange(start, end);
        },

        // 最後のページ番号の範囲
        lastPageRange() {
            if (!this.sizeCheck) return [];
            return this.calRange(this.last_page - 1, this.last_page);
        },
    },

    methods: {
        // 商品画像のパスを取得するメソッド
        getProductPicturePath(product) {
            // console.log('productは、', product);
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
            return detailLink;
        },

        // ページが変更されたときに新しい商品データを取得するメソッド
        async getProducts() {
            const result = await axios.get(`/products?page=${this.current_page}`);
            // console.log('resultは、', result);
            const products = result.data;
            console.log('productsは、', products);
            this.localProduct = products.data;
            // console.log('productsは、', this.localProduct);
            this.last_page = products.last_page;
            // console.log('this.last_pageは、', products.last_page);
        },

        // 配列作成メソッド
        calRange(start, end) {
            const range = [];
            for (let i = start; i <= end; i++) {
                range.push(i);
            }
            return range;
        },

        // ページが変更されたときの処理
        changePage(page) {
            if (page > 0 && page <= this.last_page) {
                this.current_page = page;
                this.getProducts();
            }
        },

        // ページが現在のページかどうかを確認するメソッド
        isCurrent(page) {
            return page === this.current_page;
        },

        // ページネーションの表示を制御するためのメソッド
        sizeCheck() {
            if (this.last_page <= this.range + 4) {
                return false;
            }
            return true;
        },
    },

    // ページが初期化されたときに商品データを取得する
    created() {
        // console.log(this.products);
        // console.log('画像ファイルは、', this.products.data[0].pictures[0].file);
        this.getProducts();
    },
}
</script>

<style scoped>
.pagination {
    display: flex;
    list-style-type: none;
}

.pagination li {
    border: 1px solid #ddd;
    padding: 6px 12px;
    text-align: center;
}

.active {
    background-color: #337;
    color:white;
}

.inactive{
    color: #337;
}

.pagination li + li {
    border-left: none;
}
.disabled {
    cursor: not-allowed;
}
</style>