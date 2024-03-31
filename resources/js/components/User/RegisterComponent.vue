<template>
    <div class="p-container">
        <form @submit.prevent="submitForm">

            <!-- バリデーションエラーメッセージ -->
            <span v-if="errors && errors.name" class="c-error">{{ errors.name[0] }}</span>
            <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
            <span v-if="errors && errors.password" class="c-error">{{ errors.password[0] }}</span>
            <span v-if="errors && errors.password_confirmation" class="c-error">{{ errors.password_confirmation[0] }}</span>
            <span v-if="errors && !agreement" class="c-error">利用規約に同意する必要があります</span>

            <table>
                <tr>
                    <th><label for="name" class="c-label">お名前</label></th>
                    <td>
                        <input v-model="formData.name" id="name" type="name" class="c-input" :class="{ 'is-invalid': errors && errors.name }" autocomplete="name">
                    </td>
                </tr>

                <tr>
                    <th><label for="email" class="c-label">メールアドレス</label></th>
                    <td>
                        <input v-model="formData.email" id="email" type="email" class="c-input" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">
                    </td>
                </tr>
                <tr>
                    <th><label for="password" class="c-label">パスワード</label></th>
                    <td>
                        <div class="p-input__password">
                            <input v-model="formData.password" id="password" :type="PasswordType" class="c-input" :class="{ 'is-invalid': errors && errors.password }" autocomplete="new-password" placeholder="英数字8文字以上で入力してください">
                            <span @click="togglePasswordVisibility('password')"><i :class="PasswordIconClass"></i></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th><label for="password-confirm" class="c-label">パスワード（再入力）</label></th>
                    <td>
                        <div class="p-input__password">
                            <input v-model="formData.password_confirmation" id="password-confirm" :type="PasswordConfirmType" class="c-input" :class="{ 'is-invalid': errors && errors.password_confirmation }" autocomplete="new-password" placeholder="英数字8文字以上で入力してください">
                            <span @click="togglePasswordVisibility('password_confirm')"><i :class="PasswordConfirmIconClass"></i></span>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- 利用規約 -->
            <div class="p-container__terms">
                <div v-html="terms"></div>
            </div>

            <div class="p-container__checkbox">
                <input type="checkbox" v-model="agreement" id="agreement">
                <span class="c-text__center" for="agreement">利用規約に同意します</span>
            </div>

            <!-- 登録ボタン -->
            <div class="p-register__button">
                <button type="submit" class="c-button">登録する</button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            formData: {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                role: 'user',
            },
            terms: '', // 利用規約
            agreement: false, // 利用規約に同意したかどうか
            errors: null,
            PasswordType: 'password', // パスワードの初期設定
            PasswordConfirmType: 'password', // パスワード（再入力）の初期設定
            PasswordIconClass: 'far fa-eye-slash', // 初期アイコン
            PasswordConfirmIconClass: 'far fa-eye-slash', // 初期アイコン
        };
    },

    mounted() {
        // 外部ファイル「terms.blade.php」の内容を取得
        this.getTerms();
    },

    methods: {
        // 利用規約の内容を取得するメソッド
        getTerms() {
            axios.get('/terms').then(response => {
                this.terms = response.data;
            }).catch(error => {
                console.error('利用規約の取得に失敗しました:', error);
            });
        },

        // 入力された値をサーバー側に送信するメソッド
        submitForm() {
            this.formData.agreement = this.agreement;
            // 利用規約に同意している場合は、フォームを送信
            axios.post('register', this.formData).then(response => {
                window.location.href = '/home'; // 【TODO】 /homeに画面遷移しているので修正
            }).catch(error => {
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