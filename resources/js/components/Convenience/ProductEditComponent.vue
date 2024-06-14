<template>
    <main class="l-main">
        <div class="l-main__header">
            <h1 class="c-title">商品編集</h1>
        </div>
        <div class="l-article">
            <div class="l-article__main">
                <form @submit.prevent="submitForm" class="c-form">
                    <!-- 商品名 -->
                    <label for="name" class="c-label">商品名<span class="c-badge">必須</span></label>
                    <span v-if="errors && errors.name" class="c-error">{{ errors.name[0] }}</span>
                    <input v-model="formData.name" id="name" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.name }" autocomplete="name">
                    <!-- 価格 -->
                    <label for="price" class="c-label">価格<span class="c-badge">必須</span></label>
                    <span v-if="errors && errors.price" class="c-error">{{ errors.price[0] }}</span>
                    <div>
                        <input v-model="formData.price" id="price" type="number" class="c-input c-input--price" maxlength="4" :class="{ 'is-invalid': errors && errors.price }" autocomplete="price">
                        <span class="c-text">円（税込）</span>
                    </div>
                    <!-- カテゴリ名 -->
                    <label for="category" class="c-label">カテゴリ名<span class="c-badge">必須</span></label>
                    <span v-if="errors && errors.category" class="c-error">{{ errors.category[0] }}</span>
                    <select v-model="formData.category" id="category" class="c-input" :class="{ 'is-invalid': errors && errors.category }">
                        <option value="">カテゴリを選択してください</option>
                        <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                    </select>
                    <!-- 賞味期限 -->
                    <label for="expiration_date" class="c-label">賞味期限<span class="c-badge">必須</span></label>
                    <span v-if="errors && errors.expiration_date" class="c-error">{{ errors.expiration_date[0] }}</span>
                    <div class="c-form--expiration">
                        <div class="c-input--date">
                            <input v-model="formData.expiration_year" id="expiration_year" type="text" class="c-input" placeholder="YYYY" maxlength="4" :class="{ 'is-invalid': errors && errors.expiration_date }">
                            <label for="expiration_year" class="c-label">年</label>
                        </div>
                        <div class="c-input--date">
                            <input v-model="formData.expiration_month" id="expiration_month" type="text" class="c-input" placeholder="MM" maxlength="2" :class="{ 'is-invalid': errors && errors.expiration_date }">
                            <label for="expiration_month" class="c-label">月</label>
                        </div>
                        <div class="c-input--date">
                            <input v-model="formData.expiration_day" id="expiration_day" type="text" class="c-input" placeholder="DD" maxlength="2" :class="{ 'is-invalid': errors && errors.expiration_date }">
                            <label for="expiration_day" class="c-label">日</label>
                        </div>
                    </div>
                    <!-- 商品画像 -->
                    <label for="product_picture" class="c-label">商品画像<span class="c-badge">必須</span></label>
                    <span v-if="errors && errors.product_picture" class="c-error">{{ errors.product_picture[0] }}</span>
                    <div class="c-product__picture" @drop="handleDrop" :class="{ 'is-invalid': errors && errors.product_picture }">
                        <input type="file" id="product_picture" @change="handleFileChange" class="c-input--hidden">
                        <img v-if="!picturePreview && formData.product_picture !== ''" :src="formData.product_picture" alt="アップロード商品画像" class="c-product__picture--img">
                        <img v-else-if="picturePreview" :src="picturePreview" alt="アップロード商品画像" class="c-product__picture--img">
                        <img v-else src="https://haikishare.com/product_pictures/no_image.png" alt="NO_IMAGE" class="c-product__picture--img">
                    </div>

                    <!-- 商品更新ボタン -->
                    <button type="submit" class="c-button c-button--submit c-button--main">商品を更新する</button>
                    <!-- 商品削除ボタン -->
                    <button class="c-button c-button--submit c-button--primary" @click.prevent="deleteProduct">商品を削除する</button>
                </form>
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
            formData: {
                name: '', // 商品名
                price: '', // 価格
                category: '', // カテゴリ名
                expiration_year: '', // 賞味期限の年
                expiration_month: '', // 賞味期限の月
                expiration_day: '', // 賞味期限の日
                product_picture: '', // 商品画像
            },
            categories: [], // 商品カテゴリ
            picturePreview: '', // 商品画像のプレビュー
            convenience_name: '', // コンビニ名
            branch_name: '', // 支店名
            prefecture: '', // 都道府県
            city: '', // 市区町村
            town: '', // 地名・番地
            building: '', // 建物名・部屋番号
            introduction: '', // 自己紹介文
            errors: null, // エラーメッセージ
        };
    },

    computed: {
        // ログインユーザーかどうか
        isLogin() {
            return this.$store.getters['auth/check'];
        },

        // 賞味期限日付の入力値をYYYY-MM-DD形式に直すメソッド
        formattedExpirationDate() {
            const year = this.formData.expiration_year; // 年の部分を取得
            const month = this.formData.expiration_month; // 月の部分を取得
            const day = this.formData.expiration_day; // 日の部分を取得
            // 年、月、日がすべて入力されている場合のみ処理を行う
            if (year && month && day) {
                // 新しいDateオブジェクトを作成し、入力された年月日をセット
                const date = new Date(year, month - 1, day); // 月は0から始まるため、1を引く
                // toLocaleString()メソッドを使用して日付をロケールに応じた文字列に変換し、返す
                return date.toLocaleString('ja-JP', { year: 'numeric', month: '2-digit', day: '2-digit' });
            } else {
                return ''; // 年、月、日のいずれかが入力されていない場合は空文字を返す
            }
        }
    },

    created() {
        this.productId = this.$route.params.productId; // ルートからproductIdを取得
        this.getCategories(); // インスタンス初期化時に商品カテゴリ情報を読み込む
        this.getProduct(); // インスタンス初期化時に商品情報を読み込む
        this.getSidebarProfile(); // インスタンス初期化時にサイドバーに表示するプロフィール情報を読み込む
    },

    methods: {
        // 商品カテゴリ情報をサーバーから取得
        getCategories() {
            // 商品カテゴリ情報の取得APIをGET送信
            axios.get('/api/categories').then(response => {
                this.categories = response.data.categories; // レスポンスデータのカテゴリ情報をcategoriesプロパティにセット
            }).catch(error => {
                this.errors = error.response.data;
            });
        },

        // 商品情報をサーバーから取得
        getProduct() {
            // 商品情報取得APIをGET送信
            axios.get('/api/products/'+ this.productId).then(response => { // 商品IDを含むリクエスト
                this.product = response.data.product; // レスポンスデータの商品情報をproductプロパティにセット
                // 取得した各商品情報をformDataに入れる
                this.formData.name = this.product.name || ''; // 商品名
                this.formData.price = this.product.price || ''; // 価格
                this.formData.category = this.product.category.id || ''; // カテゴリ名
                // 賞味期限を年、月、日に分割して設定
                if (this.product.expiration_date) {
                    this.formData.expiration_year = this.product.expiration_date.substring(0, 4) || ''; // 年
                    this.formData.expiration_month = this.product.expiration_date.substring(5, 7) || ''; // 月
                    this.formData.expiration_day = this.product.expiration_date.substring(8, 10) || ''; // 日
                } else {
                    this.formData.expiration_year = ''; // 賞味期限がない場合は空文字列に設定
                    this.formData.expiration_month = '';
                    this.formData.expiration_day = '';
                }
                this.formData.product_picture = this.product.pictures[0].file || ''; // 商品画像
            }).catch(error => {
                this.errors = error.response.data;
            });
        },

        // 入力された値をサーバー側に送信するメソッド
        submitForm() {
            const productId = this.productId; // 商品ID
            // リクエストヘッダー定義
            const config = {
                headers: {
                    'content-type': 'multipart/form-data' // ファイルのアップロードを含むリクエストボディのデータ形式
                }
            };
            // フォームデータを作成
            const formData = new FormData();  // FormDataオブジェクトの作成
            formData.append('_method', 'PUT'); // リクエストメソッドをPUTにする
            formData.append('name', this.formData.name); // 商品名
            formData.append('price', this.formData.price); // 価格
            formData.append('category', this.formData.category); // 商品カテゴリ
            formData.append('expiration_date', this.formattedExpirationDate); // 賞味期限
            formData.append('product_picture', this.formData.product_picture); // 商品画像
            // 商品編集APIをPOST送信
            axios.post('/api/convenience/products/edit/' + productId, formData, config).then(response => { // 商品IDとリクエストヘッダとフォームデータを含むリクエスト
                console.log('product_picturesは、', this.formData.product_picture);
                this.$router.push({ name: 'convenience.products.sale' }); // 商品更新処理後、出品した商品一覧画面に遷移
            }).catch(error => {
                this.errors = error.response.data.errors;
            });
        },

        // 商品削除処理をサーバー側に送信するメソッド
        deleteProduct() {
            const productId = this.productId; // 商品ID
            // 商品削除APIをDELETE送信
            axios.delete('/api/convenience/products/' + productId).then(response => {
                this.$router.push({ name: 'convenience.products.sale' }); // 商品削除処理後、出品した商品一覧画面に遷移
            }).catch(error => {
                this.errors = error.response.data.errors;
            });
        },

        // ドラッグ＆ドロップエリアに画像がドロップされたときの処理
        handleDrop(event) {
            event.preventDefault(); // デフォルトの動作をキャンセル
            event.dataTransfer.files[0]; // 最初のファイルを取得
        },

        // ファイルが選択されたときの処理
        handleFileChange(event) {
            const file = event.target.files[0]; // 最初のファイルを取得
            if (file) {
                this.previewImage(file); // プレビューを表示する
                this.formData.product_picture = file; // formData.product_pictureにファイルオブジェクトを設定
            } else {
                this.formData.product_picture = null; // ファイルがない場合はnull
            }
        },

        // 画像のプレビューを表示するメソッド
        previewImage(file) {
            const reader = new FileReader(); // FileReaderオブジェクトの作成
            reader.onload = (e) => { // 画像の読み込み
                this.picturePreview = e.target.result; // プレビュー画像のURLを生成し、formDataに設定
            };
            reader.readAsDataURL(file); // ファイルをデータURLとして読み込み
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
}
</script>