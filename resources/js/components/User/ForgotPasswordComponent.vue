<template>
    <main class="l-main">
        <section class="l-main__wrapper">
            <h1 class="c-title u-mt__xl u-mb__xl">利用者パスワード変更メール送信</h1>
            <!-- フラッシュメッセージを表示 -->
            <Toast />

            <form @submit.prevent="sendResetLink" class="c-form">

                <!-- メールアドレス -->
                <label for="email" class="c-label">メールアドレス<span class="c-required">必須</span></label>
                <span v-if="errors && errors.email" class="c-error u-mt__s u-mb__s">{{ errors.email[0] }}</span>
                <input v-model="formData.email" id="email" type="text" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">

                <!-- メール送信ボタン -->
                <button type="submit" class="c-button c-button__submit c-button__main u-pd__s u-mt__m">パスワード変更メールを送信する</button>

            </form>
        </section>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
import Toast from '../Toast.vue'; // Toastコンポーネントをインポート

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

    methods: {
        // パスワードリセットメール送信処理
        sendResetLink() {
            // 利用者パスワード変更メール送信APIをPOST送信
            axios.post('/api/user/password/email', this.formData).then(response => { // formDataを含めたリクエスト
                this.$store.dispatch('flash/setFlashMessage', { // フラッシュメッセージの表示
                    message: 'パスワード変更メールを送信しました。',
                    type: 'success'
                });
            }).catch(error => {
                this.$store.dispatch('flash/setFlashMessage', { // フラッシュメッセージの表示
                    message: 'パスワード変更メールを送信できませんでした。',
                    type: 'error'
                });
                console.log('errorは、', error);
                console.log('メール送信失敗：', error.response.data);
                this.errors = error.response.data.errors;
            });
        }
    }
};
</script>