<template>
    <main class="l-main">
        <div class="l-main__convenience">
            <section class="l-main__wrapper">
                <h1 class="c-title u-mb__xl">コンビニ商品編集画面</h1>
                <form @submit.prevent="submitForm" class="c-form">

                    <!-- バリデーションエラーメッセージ -->
                    <span v-if="errors && errors.name" class="c-error u-mt__s u-mb__s">{{ errors.name[0] }}</span>
                    <span v-if="errors && errors.price" class="c-error u-mt__s u-mb__s">{{ errors.price[0] }}</span>
                    <span v-if="errors && errors.category" class="c-error u-mt__s u-mb__s">{{ errors.category[0] }}</span>
                    <span v-if="errors && errors.expiration_date" class="c-error u-mt__s u-mb__s">{{ errors.expiration_date[0] }}</span>
                    <span v-if="errors && errors.product_picture" class="c-error u-mt__s u-mb__s">{{ errors.product_picture[0] }}</span>

                    <!-- 商品名 -->
                    <label for="name" class="c-label">商品名</label>
                    <input v-model="formData.name" id="name" type="text" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.name }" autocomplete="name">
                    <!-- 価格 -->
                    <label for="price" class="c-label">価格</label>
                    <input v-model="formData.price" id="price" type="number" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.price }" autocomplete="price">
                    <!-- カテゴリ名 -->
                    <label for="category" class="c-label">カテゴリ名</label>
                    <select v-model="formData.category" id="category" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.category }">
                        <option value="">カテゴリを選択してください</option>
                        <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                    </select>
                    <!-- 賞味期限 -->
                    <label for="expiration_date" class="c-label">賞味期限</label>
                    <div class="p-text__form">
                        <input v-model="formData.expiration_date" id="expiration_date" type="text" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.expiration_date }" placeholder="（例）2024年4月10日の場合　20240410　と入力">
                        <span class="c-text c-text__attention">賞味期限は西暦・半角数字8桁で入力してください</span>
                    </div>
                    <!-- 商品画像 -->
                    <label for="product_picture" class="c-label">商品画像</label>
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
        </div>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            formData: {
                name: '',
                price: '',
                category: '',
                expiration_date: '',
                product_picture: '',
            },
            categories: [],
            picturePreview: '',
            errors: null,
        };
    },

    computed: {
        // 賞味期限日付の入力値をYYYY-MM-DD形式に直すメソッド
        formattedExpirationDate() {
            const inputDate = this.formData.expiration_date;
            
            // YYYYMMDD形式に直す
            if (inputDate && inputDate.length === 8) {
                const year = inputDate.substring(0, 4); // YYYYの部分を取り出す
                const month = inputDate.substring(4, 6); // MMの部分を取り出す
                const day = inputDate.substring(6, 8); // DDの部分を取り出す
                // YYYYMMDD形式に変換して返す
                return `${year}${month}${day}`;
            } else {
                // 入力が不正な場合は空文字を返す
                return '';
            }
        }
    },

    created() {
        this.productId = this.$route.params.productId; // ルートからproductIdを取得
        this.getProduct(); // 商品情報を取得
        this.getCategories(); // 商品カテゴリー情報を取得
    },

    methods: {
        // 商品カテゴリー情報をサーバーから取得
        getCategories() {
            console.log('商品カテゴリーを取得します');
            axios.get('/api/categories').then(response => {
                this.categories = response.data.categories;
            }).catch(error => {
                console.error('商品カテゴリー情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // 商品情報をサーバーから取得
        getProduct() {
            console.log('商品情報を取得します');
            axios.get('/api/products/'+ this.productId).then(response => {
                this.product = response.data.product;
                console.log('APIからのレスポンス:', response.data);
                // 取得した商品情報をformDataに入れる
                this.formData.name = this.product.name || '';
                this.formData.price = this.product.price || '';
                this.formData.category = this.product.category.id || '';
                this.formData.expiration_date = this.product.expiration_date.replace(/-/g, '') || ''; // YY-MM-DD形式からハイフンだけを取り除く
                this.formData.product_picture = this.product.pictures[0].file || '';
            }).catch(error => {
                console.error('商品情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // 入力された値をサーバー側に送信するメソッド
        submitForm() {
            const productId = this.productId;
            
            // リクエストヘッダー定義
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            };

            // フォームデータを作成
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', this.formData.name);
            formData.append('price', this.formData.price);
            formData.append('category', this.formData.category);
            formData.append('expiration_date', this.formattedExpirationDate);
            formData.append('product_picture', this.formData.product_picture);

            axios.post('/api/convenience/products/edit/' + productId, formData, config).then(response => {
                this.message = response.data.message;
                console.log('this.messageは、', this.message);
                console.log('商品情報を更新します。');
                this.$router.push({ name: 'convenience.products.sale' }); // 商品更新処理後、出品した商品一覧画面に遷移
            }).catch(error => {
                console.error('商品編集失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        },

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
        },

        // ドラッグ＆ドロップエリアに画像がドロップされたときの処理
        handleDrop(event) {
            event.preventDefault();
            event.dataTransfer.files[0];
        },

        // ファイルが選択されたときの処理
        handleFileChange(event) {
            const file = event.target.files[0];
            console.log('選択されたファイル:', file);
            // プレビューを表示する
            this.previewImage(file);
            // formData.product_pictureにファイルオブジェクトを設定
            this.formData.product_picture = file;
        },

        // 画像のプレビューを表示するメソッド
        previewImage(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                // プレビュー画像のURLを生成し、formDataに設定
                this.picturePreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}
</script>
