<template>
    <main class="l-main">
        <div class="l-main__convenience">
            <section class="p-register">
                <h1 class="c-title u-mb__xl">コンビニログイン</h1>
                <form @submit.prevent="submitForm" class="c-form">

                    <!-- バリデーションエラーメッセージ -->
                    <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
                    <span v-if="errors && errors.password" class="c-error">{{ errors.password[0] }}</span>

                    <table>
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
                                    <input v-model="formData.password" id="password" :type="PasswordType" class="c-input" :class="{ 'is-invalid': errors && errors.password }" placeholder="英数字8文字以上で入力してください">
                                    <span @click="togglePasswordVisibility('password')"><i :class="PasswordIconClass"></i></span>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <!-- パスワード保持 -->
                    <div class="c-checkbox c-checkbox__container">
                        <input type="checkbox" v-model="remember" id="remember">
                        <span class="c-text__center" for="remember">パスワードを保持する</span>
                    </div>

                    <!-- パスワードリマインダー -->
                    <a class="c-link" href="/convenience/password/email">パスワードをお忘れの場合はこちら</a>

                    <!-- ログインボタン -->
                    <button type="submit" class="c-button c-button__submit c-button__convenience u-mt__m">ログインする</button>
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
                email: '',
                password: '',
            },
            remember: false, // パスワード保持にチェックしたか
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
            axios.post('/api/convenience/login', this.formData).then(response => {
                console.log('ログインします。');
                console.log('APIからのレスポンス:', response.data);
                this.$store.dispatch('auth/currentUser');
                this.$router.push({ name: 'convenience.mypage' }); // ログイン後、マイページに遷移
            }).catch(error => {
                console.error('ログイン失敗:', error.response.data);
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
};
</script>