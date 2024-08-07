<template>
    <main class="l-main">
        <div class="l-main__header">
            <h1 class="c-title">利用者パスワード変更</h1>
        </div>
        <section class="l-main__wrapper">
            <form @submit.prevent="resetPassword" class="c-form">
                <!-- 新しいパスワード -->
                <label for="new_password" class="c-label">新しいパスワード<span class="c-badge">必須</span></label>
                <span class="c-text c-text--note u-fz-10@sm">※新しいパスワードと新しいパスワード（再入力）は、半角数字・英字大文字・小文字、記号（!@#$%^&*）を使って8文字以上で入力してください</span>
                <span v-if="errors && errors.newPassword" class="c-error">{{ errors.newPassword[0] }}</span>
                <span v-if="$v.formData.newPassword.$error && $v.formData.newPassword.$dirty" class="c-error">新しいパスワードが入力されていません。</span>
                <span v-if="$v.formData.newPassword.$error && !$v.formData.newPassword.minLength && $v.formData.newPassword.$dirty" class="c-error">新しいパスワードは、8文字以上で入力してください。</span>
                <span v-if="$v.formData.newPassword.$error && !$v.formData.newPassword.validPasswordFormat && $v.formData.newPassword.$dirty" class="c-error">新しいパスワードは半角数字・英字大文字・小文字、記号（!@#$%^&*）で入力してください。</span>
                <div class="c-password">
                    <input v-model="formData.newPassword" id="new_password" :type="NewPasswordType" class="c-input" @blur="$v.formData.newPassword.$touch()" :class="{'is-invalid': $v.formData.newPassword.$error && $v.formData.newPassword.$dirty, 'is-valid': !$v.formData.newPassword.$error && $v.formData.newPassword.$dirty}" placeholder="8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('new_password')" class="c-password__icon">
                        <i :class="NewPasswordIconClass"></i>
                    </span>
                </div>

                <!-- 新しいパスワード（再入力） -->
                <label for="password-confirm" class="c-label">新しいパスワード（再入力）<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.password_confirmation" class="c-error">{{ errors.password_confirmation[0] }}</span>
                <span v-if="$v.formData.password_confirmation.$error && $v.formData.password_confirmation.$dirty" class="c-error">新しいパスワード（再入力）が入力されていません。</span>
                <span v-if="$v.formData.password_confirmation.$error && !$v.formData.password_confirmation.minLength && $v.formData.password_confirmation.$dirty" class="c-error">新しいパスワード（再入力）は、8文字以上で入力してください。</span>
                <span v-if="$v.formData.password_confirmation.$error && !$v.formData.password_confirmation.validPasswordFormat && $v.formData.password_confirmation.$dirty" class="c-error">新しいパスワード（再入力）は半角数字・英字大文字・小文字、記号（!@#$%^&*）で入力してください。</span>
                <div class="c-password">
                    <input v-model="formData.password_confirmation" id="password-confirm" :type="PasswordConfirmType" class="c-input" @blur="$v.formData.password_confirmation.$touch()" :class="{'is-invalid': $v.formData.password_confirmation.$error && $v.formData.password_confirmation.$dirty, 'is-valid': !$v.formData.password_confirmation.$error && $v.formData.password_confirmation.$dirty}" placeholder="8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('password_confirm')" class="c-password__icon">
                        <i :class="PasswordConfirmIconClass"></i>
                    </span>
                </div>

                <!-- パスワード再設定のトークンとメールアドレスの隠しフィールド -->
                <input type="hidden" name="token" v-model="token">
                <input type="hidden" name="email" v-model="email">
                <!-- メール送信ボタン -->
                <button type="submit" class="c-button c-button--submit c-button--main">パスワードを変更する</button>
            </form>
        </section>
    </main>
</template>

<script>
import { required, minLength, helpers } from 'vuelidate/lib/validators'; // Vuelidateからバリデータをインポート
const validPasswordFormat = helpers.regex('validPasswordFormat', /^[a-zA-Z0-9!@#$%^&*]+$/); // パスワードとパスワード（再入力）の正規表現バリデーション

export default {
    data() {
        return {
            formData: {
                newPassword: '', // 新しいパスワード
                password_confirmation: '', // パスワード（再入力）
            },
            token: '', // トークン
            email: '', // メールアドレス
            errors: null, // エラーメッセージ
            NewPasswordType: 'password', // 新しいパスワードの初期設定
            PasswordConfirmType: 'password', // パスワード（再入力）の初期設定
            NewPasswordIconClass: 'far fa-eye-slash', // 新しいパスワードの初期アイコン
            PasswordConfirmIconClass: 'far fa-eye-slash', // 新しいパスワード（再入力）の初期アイコン
        }
    },

    validations: { // フロント側のバリデーション
        formData: {
            newPassword: {
                required,
                validPasswordFormat,
                minLength: minLength(8),
            },
            password_confirmation: {
                required,
                validPasswordFormat,
                minLength: minLength(8),
            },
        },
    },

    mounted() {
        // URLからトークンとメールアドレスを取得
        this.token = this.$route.params.token;
        this.email = this.$route.query.email;
    },

    methods: {
        // パスワードリセット処理
        resetPassword() {
            // トークン・メールアドレスを含めたデータを作成
            const requestData = {
                token: this.token, // トークン
                email: this.email, // メールアドレス
                newPassword: this.formData.newPassword, // 新しいパスワード
                password_confirmation: this.formData.password_confirmation, // パスワード（再入力）
            };
            // 利用者パスワード変更APIをPOST送信
            axios.post('/api/user/password/reset', requestData).then(response => { // トークンとメールアドレスを含めたデータを含むリクエスト
                this.$router.push({ name: 'user.login' }); // パスワード変更後、ログイン画面に遷移
            }).catch(error => {
                this.errors = error.response.data.errors;
            });
        },

        // パスワードの表示・非表示を切り替えるメソッド
        togglePasswordVisibility(type) {
            if (type === 'new_password') { // 新しいパスワードの入力フォーム
                this.NewPasswordType = this.NewPasswordType === 'password' ? 'text' : 'password'; // PasswordTypeによってパスワードの表示・非表示を切り替え
                this.NewPasswordIconClass = this.NewPasswordIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash'; // PasswordIconClassによってパスワードのアイコンを切り替え
            } else if (type === 'password_confirm') { // 新しいパスワード（再入力）の入力フォーム
                this.PasswordConfirmType = this.PasswordConfirmType === 'password' ? 'text' : 'password'; // PasswordTypeによってパスワードの表示・非表示を切り替え
                this.PasswordConfirmIconClass = this.PasswordConfirmIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash'; // PasswordIconClassによってパスワードのアイコンを切り替え
            }
        }
    }
};
</script>