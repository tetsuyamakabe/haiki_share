<template>
    <main class="l-main">
        <section class="l-main__wrapper">
            <h1 class="c-title u-mb__xl">コンビニ商品編集画面</h1>
            <form @submit.prevent="submitForm" class="c-form">

                <!-- 商品名 -->
                <label for="name" class="c-label">商品名</label>
                <span v-if="errors && errors.name" class="c-error u-mt__s u-mb__s">{{ errors.name[0] }}</span>
                <input v-model="formData.name" id="name" type="text" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.name }" autocomplete="name">

                <!-- 価格 -->
                <label for="price" class="c-label">価格</label>
                <span v-if="errors && errors.price" class="c-error u-mt__s u-mb__s">{{ errors.price[0] }}</span>
                <input v-model="formData.price" id="price" type="number" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.price }" autocomplete="price">

                <!-- カテゴリ名 -->
                <label for="category" class="c-label">カテゴリ名</label>
                <span v-if="errors && errors.category" class="c-error u-mt__s u-mb__s">{{ errors.category[0] }}</span>
                <select v-model="formData.category" id="category" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.category }">
                    <option value="">カテゴリを選択してください</option>
                    <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                </select>

                <!-- 賞味期限 -->
                <label for="expiration_date" class="c-label">賞味期限</label>
                <span v-if="errors && errors.expiration_date" class="c-error u-mt__s u-mb__s">{{ errors.expiration_date[0] }}</span>
                <div class="p-text__form">
                    <input v-model="formData.expiration_date" id="expiration_date" type="text" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.expiration_date }" placeholder="（例）2024年4月10日の場合　20240410　と入力">
                    <span class="c-text c-text__attention">賞味期限は西暦・半角数字8桁で入力してください</span>
                </div>

                <!-- 商品画像 -->
                <label for="product_picture" class="c-label">商品画像</label>
                <span v-if="errors && errors.product_picture" class="c-error u-mt__s u-mb__s">{{ errors.product_picture[0] }}</span>
                <div class="p-product__picture p-product__picture--container u-pd__s" @drop="handleDrop" :class="{ 'is-invalid': errors && errors.product_picture }">
                    <input type="file" id="product_picture" @change="handleFileChange" class="c-input__hidden">
                    <img v-if="!picturePreview && formData.product_picture !== ''" :src="'/storage/product_pictures/' + formData.product_picture" alt="アップロード商品画像" class="c-product__picture">
                    <img v-else-if="picturePreview" :src="picturePreview" alt="アップロード商品画像" class="c-product__picture">
                    <img v-else src="/storage/product_pictures/no_image.png" alt="NO_IMAGE" class="c-product__picture">
                </div>

                <!-- 商品更新ボタン -->
                <button type="submit" class="c-button c-button__submit c-button__convenience u-pd__s u-mt__m">更新する</button>
                <!-- 商品削除ボタン -->
                <button class="c-button c-button__submit c-button__convenience u-pd__s u-mt__m" @click="deleteProduct">削除する</button>

            </form>
        </section>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            formData: {
                name: '', // 商品名
                price: '', // 価格
                category: '', // カテゴリ名
                expiration_date: '', // 賞味期限
                product_picture: '', // 商品画像
            },
            categories: [], // 商品カテゴリ
            picturePreview: '', // 商品画像のプレビュー
            errors: null, // エラーメッセージ
        };
    },

    computed: {
        // 賞味期限日付の入力値をYYYY-MM-DD形式に直すメソッド
        formattedExpirationDate() {
            const inputDate = this.formData.expiration_date; // 賞味期限フォームの入力値
            // 賞味期限日付の形式をYYYY-MM-DD形式に直す
            if (inputDate && inputDate.length === 8) {
                const year = inputDate.substring(0, 4); // YYYYの部分を取り出す
                const month = inputDate.substring(4, 6); // MMの部分を取り出す
                const day = inputDate.substring(6, 8); // DDの部分を取り出す
                return `${year}-${month}-${day}`; // YYYY-MM-DD形式に変換して返す
            } else {
                return ''; // 入力が不正な場合は空文字を返す
            }
        }
    },

    created() {
        this.productId = this.$route.params.productId; // ルートからproductIdを取得
        this.getProduct(); // インスタンス初期化時に商品情報を読み込む
        this.getCategories(); // インスタンス初期化時に商品カテゴリ情報を読み込む
    },

    methods: {
        // 商品カテゴリ情報をサーバーから取得
        async getCategories() {
            try {
                // 商品カテゴリ情報の取得APIをGET送信
                const response = await axios.get('/api/categories');
                this.categories = response.data.categories; // レスポンスデータのカテゴリ情報をcategoriesプロパティにセット
            } catch (error) {
                console.error('カテゴリー情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            }
        },

        // 商品情報をサーバーから取得
        async getProduct() {
            try {
                console.log('商品情報を取得します');
                // 商品情報取得APIをGET送信
                const response = await axios.get(`/api/products/${this.productId}`);
                this.product = response.data.product; // レスポンスデータの商品情報をproductプロパティにセット
                console.log('APIからのレスポンス:', response.data);
                // 取得した商品情報をformDataに入れる
                this.formData.name = this.product.name || ''; // 商品名
                this.formData.price = this.product.price || ''; // 価格
                this.formData.category = this.product.category.id || ''; // カテゴリ名
                this.formData.expiration_date = this.product.expiration_date.replace(/-/g, '') || ''; // 賞味期限、YY-MM-DD形式からハイフンだけを取り除く
                this.formData.product_picture = this.product.pictures[0].file || ''; // 商品画像
            } catch (error) {
                console.error('商品情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            }
        },

        // 入力された値をサーバー側に送信するメソッド
        async submitForm() {
            try {
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
                const response = await axios.post(`/api/convenience/products/edit/${productId}`, formData, config); // 商品IDとリクエストヘッダとフォームデータを含むリクエスト
                this.message = response.data.message;
                console.log('this.messageは、', this.message);
                console.log('商品情報を更新します。');
                this.$router.push({ name: 'convenience.products.sale' }); // 商品更新処理後、出品した商品一覧画面に遷移
            } catch (error) {
                console.error('商品編集失敗:', error.response.data);
                this.errors = error.response.data.errors;
            }
        },

        // 商品削除処理をサーバー側に送信するメソッド
        async deleteProduct() {
            try {
                const productId = this.productId; // 商品ID
                // 商品削除APIをDELETE送信
                await axios.delete(`/api/convenience/products/${productId}`);
                console.log('商品情報を削除します。');
                this.$router.push({ name: 'convenience.products.sale' }); // 商品削除処理後、出品した商品一覧画面に遷移
            } catch (error) {
                console.error('商品削除失敗:', error.response.data);
                this.errors = error.response.data.errors;
            }
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
                this.formData.product_picture = file; / // formData.product_pictureにファイルオブジェクトを設定
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
        }
    }
}
</script>