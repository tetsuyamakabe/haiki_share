<template>
    <main class="l-main">
        <section class="l-main__wrapper">
            <h1 class="c-title u-mb__xl">利用者ユーザー登録</h1>
            <form @submit.prevent="submitForm" class="c-form">

                <!-- お名前 -->
                <label for="name" class="c-label">お名前<span class="c-required">必須</span></label>
                <span v-if="errors && errors.name" class="c-error u-mt__s">{{ errors.name[0] }}</span>
                <input v-model="formData.name" id="name" type="name" class="c-input u-pd__s u-mt__s u-mb__s" :class="{ 'is-invalid': errors && errors.name }" autocomplete="name">

                <!-- メールアドレス -->
                <label for="email" class="c-label">メールアドレス<span class="c-required">必須</span></label>
                <span v-if="errors && errors.email" class="c-error u-mt__s">{{ errors.email[0] }}</span>
                <input v-model="formData.email" id="email" type="email" class="c-input u-pd__s u-mt__s u-mb__s" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">

                <!-- パスワード -->
                <label for="password" class="c-label">パスワード<span class="c-required">必須</span></label>
                <span v-if="errors && errors.password" class="c-error u-mt__s">{{ errors.password[0] }}</span>
                <div class="c-input__password">
                    <input v-model="formData.password" id="password" :type="PasswordType" class="c-input u-pd__s u-mt__s u-mb__s" :class="{ 'is-invalid': errors && errors.password }" placeholder="英数字8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('password')"><i :class="PasswordIconClass"></i></span>
                </div>

                <!-- パスワード（再入力） -->
                <label for="password-confirm" class="c-label">パスワード（再入力）<span class="c-required">必須</span></label>
                <span v-if="errors && errors.password_confirmation" class="c-error u-mt__s">{{ errors.password_confirmation[0] }}</span>
                <div class="c-input__password">
                    <input v-model="formData.password_confirmation" id="password-confirm" :type="PasswordConfirmType" class="c-input u-pd__s u-mt__s u-mb__s" :class="{ 'is-invalid': errors && errors.password_confirmation }" placeholder="英数字8文字以上で入力してください">
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
                <button type="submit" class="c-button c-button__submit c-button__user u-pd__s">ユーザー登録する</button>
            </form>
        </section>

        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
import axios from 'axios';
import TermsComponent from '../TermsComponent.vue'; // 利用規約

export default {
    components: {
        'terms-component': TermsComponent // 利用規約コンポーネントを読み込み
    },

    data() {
        return {
            formData: {
                name: '', // お名前
                email: '', // メールアドレス
                password: '', // パスワード
                password_confirmation: '', // パスワード再入力
                role: 'user', // 利用者ユーザー
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
        // 入力された値をサーバー側に送信するメソッド
        async submitForm() {
            try {
                this.formData.agreement = this.agreement; // 利用規約の同意をformDataに追加
                await axios.post('/api/user/register', this.formData); // ユーザー登録APIをPOST送信
                this.$router.push({ name: 'user.login' }); // ユーザー登録後、ログインページに遷移
            } catch (error) {
                console.error('ユーザー登録失敗:', error.response.data);
                this.errors = error.response.data.errors;
            }
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