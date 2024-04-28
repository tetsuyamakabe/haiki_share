<template>
    <main class="l-main">
        <div class="p-profile">
            <div class="p-product__form">
                <h1 class="c-title">商品出品</h1>
                <div class="p-container">
                    <form @submit.prevent="submitForm">

                        <!-- バリデーションエラーメッセージ -->
                        <span v-if="errors && errors.name" class="c-error">{{ errors.name[0] }}</span>
                        <span v-if="errors && errors.price" class="c-error">{{ errors.price[0] }}</span>
                        <span v-if="errors && errors.category" class="c-error">{{ errors.category[0] }}</span>
                        <span v-if="errors && errors.expiration_date" class="c-error">{{ errors.expiration_date[0] }}</span>
                        <span v-if="errors && errors.product_picture" class="c-error">{{ errors.product_picture[0] }}</span>

                        <table>
                            <tr>
                                <th><label for="name" class="c-label">商品名</label></th>
                                <td>
                                    <input v-model="formData.name" id="name" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.name }" autocomplete="name">
                                </td>
                            </tr>

                            <tr>
                                <th><label for="price" class="c-label">価格</label></th>
                                <td>
                                    <input v-model="formData.price" id="price" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.price }" autocomplete="price">
                                </td>
                            </tr>

                            <tr>
                                <th><label for="category" class="c-label">カテゴリ名</label></th>
                                <td>
                                    <select v-model="formData.category" id="category" class="c-input" :class="{ 'is-invalid': errors && errors.category }">
                                        <option value="">カテゴリを選択してください</option>
                                        <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th><label for="expiration_date" class="c-label">賞味期限</label></th>
                                <td>
                                    <div class="p-text__form">
                                        <input v-model="formData.expiration_date" id="expiration_date" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.expiration_date }" placeholder="（例）2024年4月10日の場合　20240410　と入力">
                                        <span class="c-text">賞味期限は半角数字で入力してください</span>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <th><label class="c-label">商品画像</label></th>
                                <td>
                                    <div v-for="(input, index) in imageInputs" :key="index" class="p-product__picture" :class="{ 'is-invalid': errors && errors.product_picture }">
                                        <input type="file" :id="'product_picture_' + index" @change="handleFileChange(index)" class="c-input__hidden">
                                        <img v-if="!picturePreview[index] && formData.product_pictures[index]" :src="'/storage/product_pictures/' + formData.product_pictures[index]" alt="アップロード商品画像" class="c-product__picture">
                                        <img v-else-if="picturePreview[index]" :src="picturePreview[index]" alt="アップロード商品画像" class="c-product__picture">
                                        <img v-else src="/storage/product_pictures/no_image.png" alt="NO_IMAGE" class="c-product__picture">
                                    </div>
                                    <button @click="addImageInput" class="c-button">画像追加</button>
                                </td>
                            </tr>

                        </table>

                        <!-- 商品出品ボタン -->
                        <div class="p-product__button">
                            <button type="submit" class="c-button">出品する</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
                product_pictures: [], // ファイルの配列を追加
            },
            categories: [],
            picturePreview: [],
            errors: null,
            imageInputs: [0], // 初期の画像入力フィールドの数
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
                // YYYY-MM-DD形式に変換して返す
                return `${year}-${month}-${day}`;
            } else {
                // 入力が不正な場合は空文字を返す
                return '';
            }
        }
    },

    created() {
        this.userId = this.$route.params.userId; // ルートからuserIdを取得
        this.getCategories(); // 商品カテゴリー情報を取得
    },

    methods: {
        // 画像入力フィールドを追加するメソッド
        addImageInput() {
            this.imageInputs.push(this.imageInputs.length);
        },

        // 商品カテゴリー情報をサーバーから取得
        getCategories() {
            axios.get('/api/categories').then(response => {
                this.categories = response.data.categories;
            }).catch(error => {
                console.error('カテゴリー情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // 入力された値をサーバー側に送信するメソッド
        submitForm() {
            const userId = this.userId;

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
             // 画像ファイルを追加
             this.formData.product_pictures.forEach((file, index) => {
                formData.append(`product_picture_${index}`, file);
            });

            axios.post('/api/convenience/products/' + userId, formData, config).then(response => {
                console.log('userIdは、', userId);
                this.message = response.data.message;
                console.log('this.messageは、', this.message);
                console.log('商品を投稿します。');
                this.$router.push({ name: 'convenience.mypage', params: { userId: userId } }); // userIdを含むマイページに遷移
            }).catch(error => {
                console.error('商品出品失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        },

        // ドラッグ＆ドロップエリアに画像がドロップされたときの処理
        handleDrop(event) {
            event.preventDefault();
            event.dataTransfer.files[0];
        },

        // ファイルが選択されたときの処理
        handleFileChange(index) {
            const file = event.target.files[0];
            console.log('選択されたファイル:', file);
            // プレビューを表示する
            this.previewImage(file, index);
            // formData.product_picturesにファイルオブジェクトを設定
            this.formData.product_pictures[index] = file;
        },

        // 画像のプレビューを表示するメソッド
        previewImage(file, index) {
            const reader = new FileReader();
            reader.onload = (e) => {
                // プレビュー画像のURLを生成し、picturePreviewに設定
                this.$set(this.picturePreview, index, e.target.result);
            };
            reader.readAsDataURL(file);
        },
    }
}
</script>