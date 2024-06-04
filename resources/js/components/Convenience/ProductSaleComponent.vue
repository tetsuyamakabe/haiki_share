<template>
    <main class="l-main">
        <div class="p-article">
            <section class="l-main__wrapper">
            <h1 class="c-title u-mt__xl u-mb__xl">コンビニ商品出品画面</h1>
                <form @submit.prevent="submitForm" class="c-form c-form__column">

                    <!-- 商品名 -->
                    <label for="name" class="c-label">商品名<span class="c-required">必須</span></label>
                    <span v-if="errors && errors.name" class="c-error u-mt__s">{{ errors.name[0] }}</span>
                    <input v-model="formData.name" id="name" type="text" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.name }" autocomplete="name">

                    <!-- 価格 -->
                    <label for="price" class="c-label">価格<span class="c-required">必須</span></label>
                    <span v-if="errors && errors.price" class="c-error u-mt__s">{{ errors.price[0] }}</span>
                    <div>
                        <input v-model="formData.price" id="price" type="text" class="c-input c-input__price u-pd__s u-mt__m u-mb__m" maxlength="4" :class="{ 'is-invalid': errors && errors.price }" autocomplete="price">
                        <span class="c-text">円（税込）</span>
                    </div>

                    <!-- カテゴリ名 -->
                    <label for="category" class="c-label">カテゴリ名<span class="c-required">必須</span></label>
                    <span v-if="errors && errors.category" class="c-error u-mt__s">{{ errors.category[0] }}</span>
                    <select v-model="formData.category" id="category" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.category }">
                        <option value="">カテゴリを選択してください</option>
                        <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                    </select>

                    <!-- 賞味期限 -->
                    <label for="expiration_date" class="c-label">賞味期限<span class="c-required">必須</span></label>
                    <span v-if="errors && errors.expiration_date" class="c-error u-mt__s">{{ errors.expiration_date[0] }}</span>
                    <div class="p-expiration u-mt__m u-mb__m">
                        <div class="c-input__date">
                            <input v-model="formData.expiration_year" id="expiration_year" type="text" class="c-input u-pd__s" placeholder="YYYY" maxlength="4" :class="{ 'is-invalid': errors && errors.expiration_date }">
                            <label for="expiration_year" class="c-label u-mr__s u-ml__s">年</label>
                        </div>
                        <div class="c-input__date">
                            <input v-model="formData.expiration_month" id="expiration_month" type="text" class="c-input u-pd__s" placeholder="MM" maxlength="2" :class="{ 'is-invalid': errors && errors.expiration_date }">
                            <label for="expiration_month" class="c-label u-mr__s u-ml__s">月</label>
                        </div>
                        <div class="c-input__date">
                            <input v-model="formData.expiration_day" id="expiration_day" type="text" class="c-input u-pd__s" placeholder="DD" maxlength="2" :class="{ 'is-invalid': errors && errors.expiration_date }">
                            <label for="expiration_day" class="c-label u-mr__s u-ml__s">日</label>
                        </div>
                    </div>

                    <!-- 商品画像 -->
                    <label class="c-label">商品画像<span class="c-required">必須</span></label>
                    <span v-if="errors && errors.product_picture" class="c-error u-mt__s">{{ errors.product_picture[0] }}</span>
                    <div class="p-product__picture p-product__picture--container u-pd__s u-pd__s u-mt__m u-mb__m" @drop="handleDrop" :class="{ 'is-invalid': errors && errors.product_picture }">
                        <input type="file" id="product_picture" @change="handleFileChange" class="c-input__hidden">
                        <img v-if="!picturePreview && formData.product_picture !== ''" :src="'https://haikishare.com/product_pictures/' + formData.product_picture" alt="アップロード商品画像" class="c-product__picture">
                        <img v-else-if="picturePreview" :src="picturePreview" alt="アップロード商品画像" class="c-product__picture">
                        <img v-else src="https://haikishare.com/product_pictures/no_image.png" alt="NO_IMAGE" class="c-product__picture">
                    </div>

                    <!-- 商品更新ボタン -->
                    <button type="submit" class="c-button c-button__submit c-button__main u-pd__s u-mt__m">商品を出品する</button>

                </form>
            </section>
            <!-- サイドバー -->
            <sidebar-component :convenience_name="convenience_name" :branch_name="branch_name" :prefecture="prefecture" :city="city" :town="town" :building="building" :introduction="introduction"></sidebar-component>
        </div>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
import axios from 'axios';
import SidebarComponent from './SidebarComponent.vue';

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
            const { expiration_year, expiration_month, expiration_day } = this.formData;

            if (expiration_year && expiration_month && expiration_day) {
                const expirationDate = new Date(expiration_year, expiration_month - 1, expiration_day);
                return expirationDate.toLocaleString('ja-JP', { year: 'numeric', month: '2-digit', day: '2-digit' });
            } else {
                return '';
            }
        }
    },

    created() {
        this.getCategories(); // インスタンス初期化時に商品カテゴリ情報を読み込む
        this.getSidebarProfile(); // インスタンス初期化時にサイドバーに表示するプロフィール情報を読み込む
    },

    methods: {
        // 商品カテゴリ情報をサーバーから取得
        getCategories() {
            // 商品カテゴリ情報の取得APIをGET送信
            axios.get('/api/categories').then(response => {
                this.categories = response.data.categories; // レスポンスデータのカテゴリ情報をcategoriesプロパティにセット
            }).catch(error => {
                console.error('商品カテゴリー情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // 入力された値をサーバー側に送信するメソッド
        submitForm() {
            // リクエストヘッダー定義
            const config = {
                headers: {
                    'content-type': 'multipart/form-data', // ファイルのアップロードを含むリクエストボディのデータ形式
                }
            };
            // フォームデータを作成
            const formData = new FormData(); // FormDataオブジェクトの作成
            formData.append('_method', 'PUT'); // リクエストメソッドをPUTにする
            formData.append('name', this.formData.name); // 商品名
            formData.append('price', this.formData.price); // 価格
            formData.append('category', this.formData.category); // カテゴリ名
            formData.append('expiration_date', this.formattedExpirationDate); // 賞味期限
            if (this.formData.product_picture !== '') {
                formData.append('product_picture', this.formData.product_picture); // 商品画像
            }
            // 商品出品APIをPOST送信
            axios.post('/api/convenience/products/sale', formData, config).then(response => { // リクエストヘッダとフォームデータを含むリクエスト
                this.$router.push({ name: 'convenience.mypage' }); // 商品出品後、マイページに遷移
            }).catch(error => {
                console.error('商品出品失敗:', error.response.data);
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
            })
            .catch(error => {
                console.error('プロフィール取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        }
    }
}
</script>