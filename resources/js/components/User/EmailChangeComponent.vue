<template>
    <main class="l-main">
        <div class="l-main__header">
            <h1 class="c-title">利用者メールアドレス変更</h1>
        </div>
        <section class="l-main__wrapper">
            <!-- フラッシュメッセージを表示 -->
            <Toast />
            <form @submit.prevent="sendVerifyLink" class="c-form">
                <!-- 古いメールアドレス -->
                <label for="old_email" class="c-label">古いメールアドレス<span class="c-badge">必須</span></label>
                <span v-if="formData.old_email" class="c-form__email">{{ formData.old_email }}</span>

                <!-- 新しいメールアドレス -->
                <label for="new_email" class="c-label">新しいメールアドレス<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.new_email" class="c-error">{{ errors.new_email[0] }}</span>
                <span v-if="$v.formData.new_email.$error && $v.formData.new_email.$dirty" class="c-error">新しいメールアドレスが入力されていません。</span>
                <span v-if="$v.formData.new_email.$error && !$v.formData.new_email.maxLength && $v.formData.new_email.$dirty" class="c-error">新しいメールアドレスは、255文字以内で入力してください。</span>
                <span v-if="$v.formData.new_email.$error && !$v.formData.new_email.email && $v.formData.new_email.$dirty" class="c-error">有効なメールアドレスを入力してください。</span>
                <input v-model="formData.new_email" id="new_email" type="text" class="c-input" @blur="$v.formData.new_email.$touch()" :class="{'is-invalid': $v.formData.new_email.$error && $v.formData.new_email.$dirty, 'is-valid': !$v.formData.new_email.$error && $v.formData.new_email.$dirty}" autocomplete="new_email">

                <!-- 新しいメールアドレス（再入力） -->
                <label for="new_email_confirmation" class="c-label">新しいメールアドレス（再入力）<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.new_email_confirmation" class="c-error">{{ errors.new_email_confirmation[0] }}</span>
                <span v-if="$v.formData.new_email_confirmation.$error && $v.formData.new_email_confirmation.$dirty" class="c-error">新しいメールアドレス（再入力）が入力されていません。</span>
                <span v-if="$v.formData.new_email_confirmation.$error && !$v.formData.new_email_confirmation.maxLength && $v.formData.new_email_confirmation.$dirty" class="c-error">新しいメールアドレス（再入力）は、255文字以内で入力してください。</span>
                <span v-if="$v.formData.new_email_confirmation.$error && !$v.formData.new_email_confirmation.email && $v.formData.new_email_confirmation.$dirty" class="c-error">有効なメールアドレスを入力してください。</span>
                <input v-model="formData.new_email_confirmation" id="new_email_confirmation" type="text" class="c-input" @blur="$v.formData.new_email_confirmation.$touch()" :class="{'is-invalid': $v.formData.new_email_confirmation.$error && $v.formData.new_email_confirmation.$dirty, 'is-valid': !$v.formData.new_email_confirmation.$error && $v.formData.new_email_confirmation.$dirty}" autocomplete="new_email_confirmation">

                <!-- 認証メール送信ボタン -->
                <button type="submit" class="c-button c-button--submit c-button--main">メールアドレス認証メールを送信する</button>
            </form>
        </section>
    </main>
</template>

<script>
import Toast from '../Parts/Toast.vue'; // Toastコンポーネント
import { required, maxLength, email } from 'vuelidate/lib/validators'; // Vuelidateからバリデータをインポート

export default {
    components: {
        Toast, // Toastコンポーネントを読み込み
    },

    data() {
        return {
            formData: {
                old_email: '', // 古いメールアドレス
                new_email: '', // 新しいメールアドレス
                new_email_confirmation: '', // 新しいメールアドレス（再入力）
            },
            errors: null // エラーメッセージ
        };
    },

    validations: { // フロント側のバリデーション
        formData: {
            old_email: {
                required,
                email,
                maxLength: maxLength(255),
            },
            new_email: {
                required,
                email,
                maxLength: maxLength(255),
            },
            new_email_confirmation: {
                required,
                email,
                maxLength: maxLength(255),
            },
        },
    },

    created() {
        this.getOldEmail(); // インスタンス初期化時に古いメールアドレス情報を読み込む
    },

    methods: {
        // 古いメールアドレス情報をサーバーから取得
        getOldEmail() {
            // 利用者側プロフィール情報の取得APIをGET送信
            axios.get('/api/user/mypage/profile').then(response => {
                this.user = response.data.user; // レスポンスデータのユーザー情報をuserプロパティにセット
                // 取得した古いメールアドレス情報をformDataに入れる
                this.formData.old_email = this.user.email || ''; // 古いメールアドレス
            }).catch (error => {
                this.errors = error.response.data;
            });
        },

        // メールアドレス認証メール送信処理
        sendVerifyLink() {
            // 利用者メールアドレス認証メール送信APIをPOST送信
            axios.post('/api/user/mypage/email', this.formData).then(response => { // formDataを含めたリクエスト
                this.$store.dispatch('flash/setFlashMessage', { // フラッシュメッセージの表示
                    message: 'メールアドレス認証メールを送信しました。',
                    type: 'success'
                });
            }).catch(error => {
                this.errors = error.response.data.errors;
            });
        }
    }
};
</script>