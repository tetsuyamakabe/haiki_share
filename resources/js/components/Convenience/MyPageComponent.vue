<template>
    <main class="l-main">
        <div class="l-main__header">
            <h1 class="c-title">コンビニマイページ</h1>
        </div>
        <div class="l-article">
            <div class="l-article__main">
                <!-- 出品した商品を最大5件表示 -->
                <div class="p-mypage">
                    <h2 class="c-title c-title--sub">出品した商品</h2><span class="c-text c-text--max">最大5件表示</span>
                    <!-- 商品情報がない場合 -->
                    <div v-if="saleProducts.length === 0">
                        <ul class="p-mypage--list"><p class="c-text">出品した商品はありません。</p></ul>
                    </div>
                    <!-- 商品情報を表示 -->
                    <div v-else>
                        <ul class="p-mypage--list">
                            <li v-for="product in saleProducts" :key="product.id" class="p-product__item">
                                <div class="c-card">
                                    <div class="c-card__header">
                                        <h3 class="c-card__name">{{ product.name }}</h3> <!-- 商品名 -->
                                    </div>
                                    <div class="c-card__body">
                                        <img class="c-card__img" :src="getProductPicturePath(product)" alt="商品画像"> <!-- 商品画像 -->
                                        <div class="c-icon">
                                            <!-- コンビニユーザーの場合にコンビニユーザーツールチップを表示 -->
                                            <div v-if="$store.getters['auth/check'] && $store.getters['auth/role'] === 'convenience'">
                                                <div class="c-tooltip">
                                                    <i class="c-icon c-icon--notlike far fa-heart"></i>{{ product.likes_count }} <!-- いいね数 -->
                                                    <div class="c-tooltip__message">コンビニユーザーはお気に入り登録できません</div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- 賞味期限までの残り日数を表示 -->
                                        <p class="c-card__text">
                                            <i class="fa-regular fa-clock"></i>
                                            <span v-if="getExpirationDate(product.expiration_date) >= 0">残り{{ getExpirationDate(product.expiration_date) }}日</span>
                                            <span v-if="getExpirationDate(product.expiration_date) < 0">賞味期限切れ</span>
                                        </p>
                                        <p class="c-card__text"><i class="fa-solid fa-calendar-days"></i>{{ formatDate(product.expiration_date) }}</p> <!-- 賞味期限日付 -->
                                        <p class="c-card__label c-card__category">{{ product.category.name }}</p> <!-- カテゴリー名 -->
                                        <p class="c-card__label c-card__price"><i class="fa-solid fa-yen-sign"></i>{{ product.price }}</p> <!-- 価格 -->
                                    </div>
                                    <div class="c-card__footer">
                                        <router-link :to="getProductDetailLink(product.id)" class="c-button c-button--default">詳細を見る</router-link>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="p-mypage--link">
                        <router-link class="c-link c-link--all" :to="{ name: 'convenience.products.sale' }">全件表示</router-link>
                    </div>
                </div>

                <!-- 購入された商品を最大5件表示 -->
                <div class="p-mypage">
                    <h2 class="c-title c-title--sub">購入された商品</h2><span class="c-text c-text--max">最大5件表示</span>
                    <!-- 商品情報がない場合 -->
                    <div v-if="purchaseProducts.length === 0">
                        <ul class="p-mypage--list"><p class="c-text">購入された商品はありません。</p></ul>
                    </div>
                    <!-- 商品情報を表示 -->
                    <div v-else>
                        <ul class="p-mypage--list">
                            <li v-for="product in purchaseProducts" :key="product.id" class="p-product__item">
                                <div class="c-card">
                                    <div class="c-card__header">
                                        <h3 class="c-card__name">{{ product.name }}</h3> <!-- 商品名 -->
                                    </div>
                                    <div class="c-card__body">
                                        <img class="c-card__img" :src="getProductPicturePath(product)" alt="商品画像"> <!-- 商品画像 -->
                                        <div class="c-icon">
                                            <!-- コンビニユーザーの場合にコンビニユーザーツールチップを表示 -->
                                            <div v-if="$store.getters['auth/check'] && $store.getters['auth/role'] === 'convenience'">
                                                <div class="c-tooltip">
                                                    <i class="c-icon c-icon--notlike far fa-heart"></i>{{ product.likes_count }} <!-- いいね数 -->
                                                    <div class="c-tooltip__message">コンビニユーザーはお気に入り登録できません</div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- 賞味期限までの残り日数を表示 -->
                                        <p class="c-card__text">
                                            <i class="fa-regular fa-clock"></i>
                                            <span v-if="getExpirationDate(product.expiration_date) >= 0">残り{{ getExpirationDate(product.expiration_date) }}日</span>
                                            <span v-if="getExpirationDate(product.expiration_date) < 0">賞味期限切れ</span>
                                        </p>
                                        <p class="c-card__text"><i class="fa-solid fa-calendar-days"></i>{{ formatDate(product.expiration_date) }}</p> <!-- 賞味期限日付 -->
                                        <p class="c-card__label c-card__category">{{ product.category.name }}</p> <!-- カテゴリー名 -->
                                        <p class="c-card__label c-card__price"><i class="fa-solid fa-yen-sign"></i>{{ product.price }}</p> <!-- 価格 -->
                                    </div>
                                    <div class="c-card__footer">
                                        <router-link :to="getProductDetailLink(product.id)" class="c-button c-button--default">詳細を見る</router-link>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="p-mypage--link">
                        <router-link class="c-link c-link--all" :to="{ name: 'convenience.products.purchase' }">全件表示</router-link>
                    </div>
                </div>
            </div>
            <!-- サイドバー -->
            <sidebar-component :convenience_name="convenience_name" :branch_name="branch_name" :prefecture="prefecture" :city="city" :town="town" :building="building" :introduction="introduction"></sidebar-component>
        </div>
    </main>
</template>

<script>
import SidebarComponent from './SidebarComponent.vue'; // サイドバーコンポーネント

export default {
    components: {
        SidebarComponent // サイドバーコンポーネントを読み込み
    },

    data() {
        return {
            saleProducts: [], // 出品した商品情報
            purchaseProducts: [], // 購入された商品情報
            convenience_name: '', // コンビニ名
            branch_name: '', // 支店名
            prefecture: '', // 都道府県
            city: '', // 市区町村
            town: '', // 地名・番地
            building: '', // 建物名・部屋番号
            introduction: '' // 自己紹介文
        };
    },

    computed: {
        // ログインユーザーかどうか
        isLogin() {
            return this.$store.getters['auth/check'];
        },
    },

    created() {
        this.getMyPageProducts(); // インスタンス初期化時にマイページに表示する出品・購入商品情報を読み込む
        this.getSidebarProfile(); // インスタンス初期化時にサイドバーに表示するプロフィール情報を読み込む
    },

    methods: {
        // マイページに表示する出品・購入商品情報の取得
        getMyPageProducts() {
            // コンビニマイページに表示する出品・購入商品情報の取得APIをGET送信
            axios.get('/api/convenience/mypage/products').then(response => {
                // レスポンスデータをそれぞれのプロパティにセット
                this.saleProducts = response.data.sale_products; // 出品した商品情報
                this.purchaseProducts = response.data.purchased_products; // 購入された商品情報
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
            return { name: 'convenience.products.detail', params: { productId: productId } }; // 商品詳細画面のリンクを返す
        },

        // サイドバーに表示するプロフィール情報の取得
        getSidebarProfile() {
            // コンビニ側プロフィール情報の取得APIをGET送信
            axios.get('/api/convenience/mypage/profile').then(response => {
                // レスポンスデータをそれぞれのプロパティにセット
                this.user = response.data.user; // ユーザー情報
                this.convenience = response.data.convenience; // コンビニ情報
                this.address = response.data.address; // 住所情報
                // 取得した各プロフィール情報をそれぞれのプロパティにセット
                this.convenience_name = this.user.name; // コンビニ名
                this.branch_name = this.convenience.branch_name; // 支店名
                this.prefecture = this.address.prefecture; // 住所
                this.city = this.address.city; // 市区町村
                this.town = this.address.town; // 地名・番地
                this.building = this.address.building; // 建物名・部屋番号
                this.introduction = this.user.introduction; // 自己紹介文
            }).catch(error => {
                this.errors = error.response.data;
            });
        }
    }
};
</script>