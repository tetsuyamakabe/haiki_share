<template>
    <main class="l-main">
        <div class="l-main__header">
            <h1 class="c-title">コンビニログイン</h1>
        </div>
        <section class="l-main__wrapper">
            <form @submit.prevent="submitForm" class="c-form">
                <!-- メールアドレス -->
                <label for="email" class="c-label">メールアドレス<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
                <span v-if="$v.formData.email.$error && $v.formData.email.$dirty" class="c-error">メールアドレスが入力されていません。</span>
                <span v-if="$v.formData.email.$error && !$v.formData.email.maxLength && $v.formData.email.$dirty" class="c-error">メールアドレスは、255文字以内で入力してください。</span>
                <span v-if="$v.formData.email.$error && !$v.formData.email.email && $v.formData.email.$dirty" class="c-error">有効なメールアドレスを入力してください。</span>
                <input v-model="formData.email" id="email" type="text" class="c-input" @blur="$v.formData.email.$touch()" :class="{'is-invalid': $v.formData.email.$error && $v.formData.email.$dirty, 'is-valid': !$v.formData.email.$error && $v.formData.email.$dirty}" autocomplete="email">

                <!-- パスワード -->
                <label for="password" class="c-label">パスワード<span class="c-badge">必須</span></label>
                <span class="c-text c-text--note u-fz-10@sm">※パスワードは、半角数字・英字大文字・小文字、記号（!@#$%^&*）を使って8文字以上で入力してください</span>
                <span v-if="errors && errors.password" class="c-error">{{ errors.password[0] }}</span>
                <span v-if="$v.formData.password.$error && $v.formData.password.$dirty" class="c-error">パスワードが入力されていません。</span>
                <span v-if="$v.formData.password.$error && !$v.formData.password.minLength && $v.formData.password.$dirty" class="c-error">パスワードは、8文字以上で入力してください。</span>
                <span v-if="$v.formData.password.$error && !$v.formData.password.validPasswordFormat && $v.formData.password.$dirty" class="c-error">パスワードは半角数字・英字大文字・小文字、記号（!@#$%^&*）で入力してください。</span>
                <div class="c-password">
                    <input v-model="formData.password" id="password" :type="PasswordType" class="c-input" @blur="$v.formData.password.$touch()" :class="{'is-invalid': $v.formData.password.$error && $v.formData.password.$dirty, 'is-valid': !$v.formData.password.$error && $v.formData.password.$dirty}" placeholder="8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('password')" class="c-password__icon">
                        <i :class="PasswordIconClass"></i>
                    </span>
                </div>

                <!-- 次回のログインを省略する -->
                <div class="c-form__group">
                    <input class="c-checkbox" type="checkbox" v-model="remember" id="remember">
                    <span class="c-text" for="remember">次回のログインを省略する</span>
                </div>
                <!-- パスワードリマインダー -->
                <a class="c-link" href="/convenience/password/email">パスワードをお忘れの場合はこちら</a>
                <!-- ログインボタン -->
                <button type="submit" class="c-button c-button--submit c-button--main">ログインする</button>
            </form>
        </section>
    </main>
</template>

<script>
import { required, maxLength, email, minLength, helpers } from 'vuelidate/lib/validators'; // Vuelidateからバリデータをインポート
const validPasswordFormat = helpers.regex('validPasswordFormat', /^[a-zA-Z0-9!@#$%^&*]+$/); // パスワードの正規表現バリデーション

export default {
    data() {
        return {
            formData: {
                email: '', // メールアドレス
                password: '', // パスワード
            },
            remember: false, // 次回ログインの省略にチェックしたか
            errors: null, // エラーメッセージ
            PasswordType: 'password', // パスワードの初期設定
            PasswordConfirmType: 'password', // パスワード（再入力）の初期設定
            PasswordIconClass: 'far fa-eye-slash', // 初期アイコン
            PasswordConfirmIconClass: 'far fa-eye-slash', // 初期アイコン
        };
    },

    validations: { // フロント側のバリデーション
        formData: {
            email: {
                required,
                email,
                maxLength: maxLength(255),
            },
            password: {
                required,
                validPasswordFormat,
                minLength: minLength(8),
            },
        },
    },

    methods: {
        // 入力された値をサーバー側に送信するメソッド
        async submitForm() {
            try {
                // コンビニログインAPIをPOST送信
                await axios.post('/api/convenience/login', {...this.formData, remember: this.remember}); // 入力値と次回ログイン省略のrememberを含むリクエスト
                await this.$store.dispatch('auth/currentUser'); // ログイン状態を保持
                this.$router.push({ name: 'convenience.mypage' }); // ログイン後、マイページ画面に遷移
            } catch (error) {
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
};
</script>