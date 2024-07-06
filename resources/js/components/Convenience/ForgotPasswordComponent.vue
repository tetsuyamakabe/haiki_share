<template>
    <main class="l-main">
        <div class="l-main__header">
            <h1 class="c-title">コンビニパスワード変更メール送信</h1>
        </div>
        <section class="l-main__wrapper">
            <!-- フラッシュメッセージを表示 -->
            <Toast />
            <form @submit.prevent="sendResetLink" class="c-form">
                <!-- メールアドレス -->
                <label for="email" class="c-label">メールアドレス<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
                <span v-if="$v.formData.email.$error && $v.formData.email.$dirty" class="c-error">メールアドレスが入力されていません。</span>
                <span v-if="$v.formData.email.$error && !$v.formData.email.maxLength && $v.formData.email.$dirty" class="c-error">メールアドレスは、255文字以内で入力してください。</span>
                <span v-if="$v.formData.email.$error && !$v.formData.email.email && $v.formData.email.$dirty" class="c-error">有効なメールアドレスを入力してください。</span>
                <input v-model="formData.email" id="email" type="text" class="c-input" @blur="$v.formData.email.$touch()" :class="{'is-invalid': $v.formData.email.$error && $v.formData.email.$dirty, 'is-valid': !$v.formData.email.$error && $v.formData.email.$dirty}" autocomplete="email">

                <!-- メール送信ボタン -->
                <button type="submit" class="c-button c-button--submit c-button--main">パスワード変更メールを送信する</button>
            </form>
        </section>
    </main>
</template>

<script>
import Toast from '../Parts/Toast.vue'; // Toastコンポーネントをインポート
import { required, maxLength, email } from 'vuelidate/lib/validators'; // Vuelidateからバリデータをインポート

export default {
    components: {
        Toast, // Toastコンポーネントを読み込み
    },

    data() {
        return {
            formData: {
                email: '', // メールアドレス
            },
            errors: null // エラーメッセージ
        };
    },

    validations: { // フロント側のバリデーション
        formData: {
            email: {
                required,
                email,
                maxLength: maxLength(255),
            },
        },
    },

    methods: {
        // パスワードリセットメール送信処理
        sendResetLink() {
            // コンビニパスワード変更メール送信APIをPOST送信
            axios.post('/api/convenience/password/email', this.formData).then(response => { // formDataを含めたリクエスト
                this.$store.dispatch('flash/setFlashMessage', { // フラッシュメッセージの表示
                    message: 'パスワード変更メールを送信しました。',
                    type: 'success'
                });
            }).catch(error => {
                this.errors = error.response.data.errors;
            });
        }
    }
};
</script>