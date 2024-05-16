<template>
    <main class="l-main">
        <div class="l-main__user">
            <section class="l-main__wrapper">
                <h1 class="c-title u-mb__xl">利用者ユーザー登録</h1>
                <form @submit.prevent="submitForm" class="c-form">

                    <!-- バリデーションエラーメッセージ -->
                    <span v-if="errors && errors.name" class="c-error">{{ errors.name[0] }}</span>
                    <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
                    <span v-if="errors && errors.password" class="c-error">{{ errors.password[0] }}</span>
                    <span v-if="errors && errors.password_confirmation" class="c-error">{{ errors.password_confirmation[0] }}</span>
                    <span v-if="errors && !agreement" class="c-error">利用規約に同意する必要があります。</span>

                    <!-- お名前 -->
                    <label for="name" class="c-label">お名前</label>
                    <input v-model="formData.name" id="name" type="name" class="c-input" :class="{ 'is-invalid': errors && errors.name }" autocomplete="name">
                    <!-- メールアドレス -->
                    <label for="email" class="c-label">メールアドレス</label>
                    <input v-model="formData.email" id="email" type="email" class="c-input" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">
                    <!-- パスワード -->
                    <label for="password" class="c-label">パスワード</label>
                    <div class="c-input__password">
                        <input v-model="formData.password" id="password" :type="PasswordType" class="c-input" :class="{ 'is-invalid': errors && errors.password }" placeholder="英数字8文字以上で入力してください">
                        <span @click="togglePasswordVisibility('password')"><i :class="PasswordIconClass"></i></span>
                    </div>
                    <!-- パスワード（再入力） -->
                    <label for="password-confirm" class="c-label">パスワード（再入力）</label>
                    <div class="c-input__password">
                        <input v-model="formData.password_confirmation" id="password-confirm" :type="PasswordConfirmType" class="c-input" :class="{ 'is-invalid': errors && errors.password_confirmation }" placeholder="英数字8文字以上で入力してください">
                        <span @click="togglePasswordVisibility('password_confirm')"><i :class="PasswordConfirmIconClass"></i></span>
                    </div>

                    <!-- 利用規約 -->
                    <div class="p-register__terms">
                        <terms-component></terms-component>
                    </div>

                    <!-- 利用規約チェックボックス -->
                    <div class="c-checkbox c-checkbox__container u-mt__m u-mb__m">
                        <input class="c-checkbox u-mr__s" type="checkbox" v-model="agreement" id="agreement">
                        <span class="c-text" for="agreement">利用規約に同意します</span>
                    </div>

                    <!-- 登録ボタン -->
                    <button type="submit" class="c-button c-button__submit c-button__user">ユーザー登録する</button>
                </form>
            </section>
        </div>
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
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                role: 'user',
            },
            agreement: false, // 利用規約に同意したかどうか
            errors: null,
            PasswordType: 'password', // パスワードの初期設定
            PasswordConfirmType: 'password', // パスワード（再入力）の初期設定
            PasswordIconClass: 'far fa-eye-slash', // 初期アイコン
            PasswordConfirmIconClass: 'far fa-eye-slash', // 初期アイコン
        };
    },

    methods: {
        // 入力された値をサーバー側に送信するメソッド
        submitForm() {
            this.formData.agreement = this.agreement;
            axios.post('/api/user/register', this.formData).then(response => {
                console.log('ユーザー登録します。');
                this.$router.push({ name: 'user.login' }); // ユーザー登録後、ログインページに遷移
            }).catch(error => {
                console.log('errorは、', error);
                console.error('ユーザー登録失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        },

        // パスワードの表示・非表示を切り替えるメソッド
        togglePasswordVisibility(type) {
            if (type === 'password') {
                this.PasswordType = this.PasswordType === 'password' ? 'text' : 'password';
                this.PasswordIconClass = this.PasswordIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash';
            } else if (type === 'password_confirm') {
                this.PasswordConfirmType = this.PasswordConfirmType === 'password' ? 'text' : 'password';
                this.PasswordConfirmIconClass = this.PasswordConfirmIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash';
            }
        }
    }
}
</script>