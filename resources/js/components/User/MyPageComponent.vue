<template>
    <main class="l-main">
        <div class="l-main__header">
            <h1 class="c-title">利用者マイページ</h1>
        </div>
        <div class="l-article">
            <div class="l-article__main">
                <!-- フラッシュメッセージを表示 -->
                <Toast />
                <!-- 購入した商品を最大5件表示 -->
                <div class="p-mypage">
                    <h2 class="c-title c-title--sub">購入した商品</h2><span class="c-text c-text--max">最大5件表示</span>
                    <!-- 商品情報がない場合 -->
                    <div v-if="purchasedProducts.length === 0">
                        <ul class="p-mypage--list"><p class="c-text">購入した商品はありません。</p></ul>
                    </div>
                    <!-- 商品情報を表示 -->
                    <div v-else>
                        <ul class="p-mypage--list">
                            <li v-for="product in purchasedProducts" :key="product.id" class="p-product__item">
                                <div class="c-card">
                                    <div class="c-card__header">
                                        <h3 class="c-card__name">{{ product.product.name }}</h3> <!-- 商品名 -->
                                    </div>
                                    <div class="c-card__body">
                                        <img class="c-card__img" :src="getProductPicturePath(product.product)" alt="商品画像"> <!-- 商品画像 -->
                                        <div class="c-icon">
                                            <!-- 利用者ユーザーの場合にいいねアイコンを表示 -->
                                            <div v-if="$store.getters['auth/check'] && $store.getters['auth/role'] === 'user'">
                                                <i v-if="!product.product.liked" class="c-icon c-icon--unlike far fa-heart" @click="productLike(product.product)"></i>
                                                <i v-else class="c-icon c-icon--like fas fa-heart" @click="productUnlike(product.product)"></i>
                                                {{ product.product.likes_count }} <!-- いいね数 -->
                                            </div>
                                        </div>
                                        <!-- 賞味期限までの残り日数を表示 -->
                                        <p class="c-card__text">
                                            <i class="fa-regular fa-clock"></i>
                                            <span v-if="getExpirationDate(product.product.expiration_date) >= 0">残り{{ getExpirationDate(product.product.expiration_date) }}日</span>
                                            <span v-if="getExpirationDate(product.product.expiration_date) < 0">賞味期限切れ</span>
                                        </p>
                                        <p class="c-card__text"><i class="fa-solid fa-calendar-days"></i>{{ formatDate(product.product.expiration_date) }}</p> <!-- 賞味期限日付 -->
                                        <p class="c-card__label c-card__category">{{ product.product.category.name }}</p> <!-- カテゴリー名 -->
                                        <p class="c-card__label c-card__price"><i class="fa-solid fa-yen-sign"></i>{{ product.product.price }}</p> <!-- 価格 -->
                                    </div>
                                    <div class="c-card__footer">
                                        <router-link :to="getProductDetailLink(product.product.id)" class="c-button c-button--default">詳細を見る</router-link>
                                        <button class="c-button c-button--default" @click="cancelPurchase(product.product.id)">購入をキャンセルする</button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="p-mypage--link">
                        <router-link class="c-link c-link--all" :to="{ name: 'user.products.purchased' }">全件表示</router-link>
                    </div>
                </div>

                <!-- お気に入りした商品を最大5件表示 -->
                <div class="p-mypage">
                    <h2 class="c-title c-title--sub">お気に入り商品</h2><span class="c-text c-text--max">最大5件表示</span>
                    <!-- 商品情報がない場合 -->
                    <div v-if="likedProducts.length === 0">
                        <ul class="p-mypage--list"><p class="c-text">お気に入り登録した商品はありません。</p></ul>
                    </div>
                    <!-- 商品情報を表示 -->
                    <div v-else>
                        <ul class="p-mypage--list">
                            <li v-for="product in likedProducts" :key="product.id" class="p-product__item">
                                <div class="c-card">
                                    <div class="c-card__header">
                                        <h3 class="c-card__name">{{ product.product.name }}</h3> <!-- 商品名 -->
                                    </div>
                                    <div class="c-card__body">
                                        <img class="c-card__img" :src="getProductPicturePath(product.product)" alt="商品画像"> <!-- 商品画像 -->
                                        <div class="c-icon">
                                            <!-- 利用者ユーザーの場合にいいねアイコンを表示 -->
                                            <div v-if="$store.getters['auth/check'] && $store.getters['auth/role'] === 'user'">
                                                <i v-if="!product.product.liked" class="c-icon c-icon--unlike far fa-heart" @click="productLike(product.product)"></i>
                                                <i v-else class="c-icon c-icon--like fas fa-heart" @click="productUnlike(product.product)"></i>
                                                {{ product.product.likes_count }} <!-- いいね数 -->
                                            </div>
                                        </div>
                                        <!-- 賞味期限までの残り日数を表示 -->
                                        <p class="c-card__text">
                                            <i class="fa-regular fa-clock"></i>
                                            <span v-if="getExpirationDate(product.product.expiration_date) >= 0">残り{{ getExpirationDate(product.product.expiration_date) }}日</span>
                                            <span v-if="getExpirationDate(product.product.expiration_date) < 0">賞味期限切れ</span>
                                        </p>
                                        <p class="c-card__text"><i class="fa-solid fa-calendar-days"></i>{{ formatDate(product.product.expiration_date) }}</p> <!-- 賞味期限日付 -->
                                        <p class="c-card__label c-card__category">{{ product.product.category.name }}</p> <!-- カテゴリー名 -->
                                        <p class="c-card__label c-card__price"><i class="fa-solid fa-yen-sign"></i>{{ product.product.price }}</p> <!-- 価格 -->
                                    </div>
                                    <div class="c-card__footer">
                                        <router-link :to="getProductDetailLink(product.product.id)" class="c-button c-button--default">詳細を見る</router-link>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="p-mypage--link">
                        <router-link class="c-link c-link--all" :to="{ name: 'user.products.liked' }">全件表示</router-link>
                    </div>
                </div>
            </div>
            <!-- サイドバー -->
            <sidebar-component :introduction="introduction"></sidebar-component>
        </div>
    </main>
</template>

<script>
import Toast from '../Parts/Toast.vue'; // Toastコンポーネント
import SidebarComponent from './SidebarComponent.vue'; // サイドバーコンポーネント

export default {
    components: {
        Toast, // Toastコンポーネントを読み込み
        SidebarComponent // サイドバーコンポーネントを読み込み
    },

    data() {
        return {
            purchasedProducts: [], // 購入された商品情報
            likedProducts: [], // お気に入り登録した商品情報
            introduction: '', //自己紹介文
        };
    },

    computed: {
        // ログインユーザーかどうか
        isLogin() {
            return this.$store.getters['auth/check'];
        },
    },

    created() {
        this.getMyPageProducts(); // インスタンス初期化時にマイページに表示する購入・お気に入り商品情報を読み込む
        this.getSidebarProfile(); // インスタンス初期化時にサイドバーに表示するプロフィール情報を読み込む
    },

    methods: {
        // マイページに表示する購入・お気に入り商品情報の取得
        getMyPageProducts() {
            // 利用者マイページに表示する出品・購入商品情報の取得APIをGET送信
            axios.get('/api/user/mypage/products').then(response => {
                // コンビニマイページに表示する出品・購入商品情報の取得APIをGET送信
                this.purchasedProducts = response.data.purchased_products; // 購入した商品情報
                this.likedProducts = response.data.liked_products; // お気に入り登録商品情報
            }).catch(error => {
                this.errors = error.response.data;
            });
        },

        // 商品画像のパスを取得するメソッド
        getProductPicturePath(product) {
            if (product.pictures.length > 0) {
                return product.pictures[0].file; // 商品画像がある場合は、その画像パスを返す
            } else {
                return 'https://haikishare.com/product_pictures/no_image.png'; // 商品画像がない場合は、デフォルトの商品画像のパスを返す
            }
        },

        // カテゴリー名を取得するメソッド
        getCategoryName(category) {
            return category && category.name ? category.name : 'その他';
        },

        // 賞味期限日付をフォーマットするメソッド
        formatDate(dateString) {
            const date = new Date(dateString); // Dateオブジェクトに変換する
            const year = date.getFullYear(); // 年数を取得
            const month = ('0' + (date.getMonth() + 1)).slice(-2); // 月数を取得、1桁の場合は2桁の数値に変換
            const day = ('0' + date.getDate()).slice(-2); // 日数を取得、1桁の場合は2桁の数値に変換
            return `${year}年${month}月${day}日`; // 年月日のフォーマットされた賞味期限日付を返す
        },

        // 賞味期限までの残り日数を計算するメソッド
        getExpirationDate(expirationDate) {
            const today = new Date(); // 今日の日付を取得
            const expiry = new Date(expirationDate); // 賞味期限の日付を取得
            const difference = expiry.getTime() - today.getTime(); // 残り日数をミリ秒で計算
            const daysRemaining = Math.ceil(difference / (1000 * 60 * 60 * 24)); // ミリ秒を日数に変換して切り上げ
            return `${daysRemaining}`; // 残り日数を表示する文字列を返す
        },

        // 商品詳細画面のリンクを返すメソッド
        getProductDetailLink(productId) {
            return { name: 'user.products.detail', params: { productId: productId } }; // 商品詳細画面のリンクを返す
        },

        // 商品購入キャンセルするメソッド
        cancelPurchase(productId) {
            // 購入キャンセルAPIをDELETE送信
            axios.delete(`/api/user/products/purchase/cancel/${productId}`).then(response => { // 商品IDを含むリクエスト
                this.$store.dispatch('flash/setFlashMessage', { // フラッシュメッセージの表示
                    message: '購入キャンセルが完了しました。購入キャンセルメールをご確認ください。',
                    type: 'success'
                });
                this.getMyPageProducts();　// 購入した商品のリストを再取得する
            }).catch(error => {
                console.error('商品購入キャンセル処理失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        },

        // 商品お気に入り登録
        productLike(product) {
            // お気に入り登録APIをPOST送信
            axios.post('/api/user/like/' + product.id).then(response => {
                product.liked = true; // いいねアイコンをtrueに切り替え
                product.likes_count++; // いいね数のインクリメント
                this.getMyPageProducts();
            }).catch(error => {
                console.error('商品のお気に入り登録失敗:', error);
            });
        },

        // 商品お気に入り解除
        productUnlike(product) {
            // お気に入り解除APIをPOST送信
            axios.post('/api/user/unlike/' + product.id).then(response => {
                product.liked = false; // いいねアイコンをfalseに切り替え
                product.likes_count--; // いいね数のデクリメント
                this.getMyPageProducts();
            }).catch(error => {
                console.error('商品のお気に入り解除失敗:', error);
            });
        },

        // サイドバーに表示するプロフィール情報の取得
        getSidebarProfile() {
            // 利用者側プロフィール情報の取得APIをGET送信
            axios.get('/api/user/mypage/profile').then(response => {
                this.user = response.data.user; // レスポンスデータのユーザー情報をuserプロパティにセット
                // 取得した各プロフィール情報をintroductionプロパティにセット
                this.introduction = this.user.introduction; // 自己紹介文
            }).catch (error => {
                this.errors = error.response.data;
            });
        },
    },
}
</script>