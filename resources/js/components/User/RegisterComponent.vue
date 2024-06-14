<template>
    <main class="l-main">
        <div class="l-main__header">
            <h1 class="c-title">利用者ユーザー登録</h1>
        </div>
        <section class="l-main__wrapper">
            <form @submit.prevent="submitForm" class="c-form">
                <!-- お名前 -->
                <label for="name" class="c-label">お名前<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.name" class="c-error">{{ errors.name[0] }}</span>
                <input v-model="formData.name" id="name" type="name" class="c-input" :class="{ 'is-invalid': errors && errors.name }" autocomplete="name">
                <!-- メールアドレス -->
                <label for="email" class="c-label">メールアドレス<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
                <input v-model="formData.email" id="email" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">
                <!-- パスワード -->
                <label for="password" class="c-label">パスワード<span class="c-badge">必須</span></label>
                <span class="c-text c-text--note u-fz-10@sm">※パスワードとパスワード（再入力）は、半角数字・英字大文字・小文字、記号（!@#$%^&*）を使って8文字以上で入力してください</span>
                <span v-if="errors && errors.password" class="c-error">{{ errors.password[0] }}</span>
                <div class="c-password">
                    <input v-model="formData.password" id="password" :type="PasswordType" class="c-input" :class="{ 'is-invalid': errors && errors.password }" placeholder="8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('password')" class="c-password__icon">
                        <i :class="PasswordIconClass"></i>
                    </span>
                </div>
                <!-- パスワード（再入力） -->
                <label for="password-confirm" class="c-label">パスワード（再入力）<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.password_confirmation" class="c-error">{{ errors.password_confirmation[0] }}</span>
                <div class="c-password">
                    <input v-model="formData.password_confirmation" id="password-confirm" :type="PasswordConfirmType" class="c-input" :class="{ 'is-invalid': errors && errors.password_confirmation }" placeholder="8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('password_confirm')" class="c-password__icon">
                        <i :class="PasswordConfirmIconClass"></i>
                    </span>
                </div>
                <!-- 利用規約 -->
                <div class="p-register">
                    <terms-component></terms-component>
                </div>
                <span v-if="errors && !agreement" class="c-error">利用規約に同意する必要があります。</span>
                <!-- 利用規約チェックボックス -->
                <div class="c-form__group">
                    <input class="c-checkbox" type="checkbox" v-model="agreement" id="agreement">
                    <span class="c-text" for="agreement">利用規約に同意します</span>
                </div>
                <!-- 登録ボタン -->
                <button type="submit" class="c-button c-button--submit c-button--main">ユーザー登録する</button>
            </form>
        </section>
    </main>
</template>

<script>
import TermsComponent from '../Common/TermsComponent.vue'; // 利用規約コンポーネント

export default {
    components: {
        TermsComponent // 利用規約コンポーネントを読み込み
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
        submitForm() {
            this.formData.agreement = this.agreement; // 利用規約の同意をformDataに追加
            // ユーザー登録APIをPOST送信
            axios.post('/api/user/register', this.formData).then(response => { // フォームデータを含むリクエスト
                this.$router.push({ name: 'user.login' }); // ユーザー登録後、ログインページに遷移
            }).catch(error => {
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