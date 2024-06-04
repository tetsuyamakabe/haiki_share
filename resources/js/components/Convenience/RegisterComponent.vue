<template>
    <main class="l-main">
        <section class="l-main__wrapper">
            <h1 class="c-title u-mt__xl u-mb__xl">コンビニユーザー登録</h1>
            <form @submit.prevent="submitForm" class="c-form">

                <!-- コンビニ名 -->
                <label for="convenience_name" class="c-label">コンビニ名<span class="c-required">必須</span></label>
                <span v-if="errors && errors.convenience_name" class="c-error u-mt__s">{{ errors.convenience_name[0] }}</span>
                <input v-model="formData.convenience_name" id="convenience_name" type="text" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.convenience_name }" autocomplete="name">

                <!-- 支店名 -->
                <label for="branch_name" class="c-label">支店名<span class="c-required">必須</span></label>
                <span v-if="errors && errors.branch_name" class="c-error u-mt__s">{{ errors.branch_name[0] }}</span>
                <input v-model="formData.branch_name" id="branch_name" type="text" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.branch_name }" autocomplete="branch_name">

                <!-- 郵便番号 -->
                <label for="zip" class="c-label">郵便番号</label>
                <span class="c-text c-text__note">※数字のみを入力し、ハイフンを含む場合は3桁と4桁の間に挿入してください。ハイフンを含まない場合は数字を7桁入力してください。</span>
                <span v-if="errors && errors.zip" class="c-error u-mt__s">{{ errors.zip[0] }}</span>
                <input v-model="formData.postalcode" id="zip" type="text" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.zip }" autocomplete="zip" placeholder="郵便番号で住所を簡単入力できます">
                <div class="p-register__zip">
                    <button type="button" class="c-button c-button__primary u-pd__s" @click="searchAddress">郵便番号検索</button>
                </div>

                <!-- 都道府県 -->
                <label for="prefecture" class="c-label">都道府県<span class="c-required">必須</span></label>
                <span v-if="errors && errors.prefecture" class="c-error u-mt__s">{{ errors.prefecture[0] }}</span>
                <input v-model="formData.prefecture" id="prefecture" type="text" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.prefecture }" autocomplete="prefecture">

                <!-- 市区町村 -->
                <label for="city" class="c-label">市区町村<span class="c-required">必須</span></label>
                <span v-if="errors && errors.city" class="c-error u-mt__s">{{ errors.city[0] }}</span>
                <input v-model="formData.city" id="city" type="text" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.city }" autocomplete="city">

                <!-- 地名・番地 -->
                <label for="town" class="c-label">地名・番地<span class="c-required">必須</span></label>
                <span v-if="errors && errors.town" class="c-error u-mt__s">{{ errors.town[0] }}</span>
                <input v-model="formData.town" id="town" type="text" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.town }" autocomplete="town">

                <!-- 建物名・部屋番号 -->
                <label for="building" class="c-label">建物名・部屋番号</label>
                <span v-if="errors && errors.building" class="c-error u-mt__s">{{ errors.building[0] }}</span>
                <input v-model="formData.building" id="building" type="text" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.building }" autocomplete="building">

                <!-- メールアドレス -->
                <label for="email" class="c-label">メールアドレス<span class="c-required">必須</span></label>
                <span v-if="errors && errors.email" class="c-error u-mt__s">{{ errors.email[0] }}</span>
                <input v-model="formData.email" id="email" type="text" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">

                <!-- パスワード -->
                <label for="password" class="c-label">パスワード<span class="c-required">必須</span></label>
                <span class="c-text c-text__note">※パスワードとパスワード（再入力）は、半角数字・英字大文字・小文字、記号（!@#$%^&*）を使って8文字以上で入力してください</span>
                <span v-if="errors && errors.password" class="c-error u-mt__s">{{ errors.password[0] }}</span>
                <div class="c-input__password">
                    <input v-model="formData.password" id="password" :type="PasswordType" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.password }" placeholder="8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('password')"><i :class="PasswordIconClass"></i></span>
                </div>

                <!-- パスワード（再入力） -->
                <label for="password-confirm" class="c-label">パスワード（再入力）<span class="c-required">必須</span></label>
                <span v-if="errors && errors.password_confirmation" class="c-error u-mt__s">{{ errors.password_confirmation[0] }}</span>
                <div class="c-input__password">
                    <input v-model="formData.password_confirmation" id="password-confirm" :type="PasswordConfirmType" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.password_confirmation }" placeholder="8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('password_confirm')"><i :class="PasswordConfirmIconClass"></i></span>
                </div>

                <!-- 利用規約 -->
                <div class="p-register__terms u-pd__s u-mt__m">
                    <terms-component></terms-component>
                </div>
                <span v-if="errors && !agreement" class="c-error u-mt__s">利用規約に同意する必要があります。</span>
                <!-- 利用規約チェックボックス -->
                <div class="c-checkbox c-checkbox__container u-mt__m u-mb__m">
                    <input class="c-checkbox u-mr__s" type="checkbox" v-model="agreement" id="agreement">
                    <span class="c-text" for="agreement">利用規約に同意します</span>
                </div>

                <!-- 登録ボタン -->
                <button type="submit" class="c-button c-button__submit c-button__main u-pd__s">ユーザー登録する</button>
            </form>
        </section>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
import axios from 'axios';
import TermsComponent from '../TermsComponent.vue'; // 利用規約
const jsonpAdapter = require('axios-jsonp') // 郵便番号API

export default {
    components: {
        'terms-component': TermsComponent // 利用規約コンポーネントを読み込み
    },

    data() {
        return {
            formData: {
                convenience_name: '', // コンビニ名
                branch_name: '', // 支店名
                prefecture: '', // 都道府県
                city: '', // 市区町村
                town: '', // 地名・番地
                building: '', // 建物名・部屋番号
                email: '', // メールアドレス
                password: '', // パスワード
                password_confirmation: '', // パスワード（再入力）
                role: 'convenience' // コンビニユーザー
            },
            agreement: false, // 利用規約に同意したかどうか
            errors: null, // エラーメッセージ
            PasswordType: 'password', // パスワードの初期設定
            PasswordConfirmType: 'password', // パスワード（再入力）の初期設定
            PasswordIconClass: 'far fa-eye-slash', // 初期アイコン
            PasswordConfirmIconClass: 'far fa-eye-slash', // 初期アイコン
        };
    },

    methods: {
        // 郵便番号検索APIを使って、郵便番号から住所を自動入力するメソッド
        searchAddress() {
            const postalcode = this.formData.postalcode; // 郵便番号フォームの入力値
            const zipCode = postalcode;
            // 郵便番号検索APIに郵便番号フォームの入力値を使ってGETリクエスト送信
            axios.get(`https://api.zipaddress.net/?zipcode=${zipCode}`, { adapter: jsonpAdapter }).then(rs => {
                // APIから返されたレスポンスデータを各入力フォームにセット
                const response = rs.data;
                this.formData.prefecture = response.pref; // 都道府県
                this.formData.city = response.city; // 市区町村
                this.formData.town = response.town; // 地名・番地
                this.formData.building = response.building; // 建物名・部屋番号
            }).catch(error => {
                console.error('住所検索エラー:', error);
            });
        },

        // 入力された値をサーバー側に送信するメソッド
        submitForm() {
            this.formData.agreement = this.agreement;  // 利用規約の同意をformDataに追加
            // ユーザー登録APIをPOST送信
            axios.post('/api/convenience/register', this.formData).then(response => { // フォームデータを含むリクエスト
                this.$router.push({ name: 'convenience.login' }); // ユーザー登録後、ログインページに遷移
            }).catch(error => {
                console.log('errorは、', error);
                console.error('ユーザー登録失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        },

        // パスワードの表示・非表示を切り替えるメソッド
        togglePasswordVisibility(type) {
            if (type === 'password') { // パスワードの入力フォーム
                this.PasswordType = this.PasswordType === 'password' ? 'text' : 'password'; // PasswordTypeによってパスワードの表示・非表示を切り替え
                this.PasswordIconClass = this.PasswordIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash'; // PasswordIconClassによってパスワードのアイコンを切り替え
            } else if (type === 'password_confirm') { // パスワード（再入力）の入力フォーム
                this.PasswordConfirmType = this.PasswordConfirmType === 'password' ? 'text' : 'password'; // PasswordTypeによってパスワードの表示・非表示を切り替え
                this.PasswordConfirmIconClass = this.PasswordConfirmIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash'; // PasswordIconClassによってパスワードのアイコンを切り替え
            }
        }
    }
}
</script>