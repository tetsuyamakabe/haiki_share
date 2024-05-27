<template>
    <main class="l-main">
        <section class="l-main__wrapper">
            <h1 class="c-title u-mb__xl">利用者パスワード変更メール送信</h1>
            <form @submit.prevent="sendResetLink" class="c-form">

                <!-- メールアドレス -->
                <label for="email" class="c-label">メールアドレス<span class="c-required">必須</span></label>
                <span v-if="errors && errors.email" class="c-error u-mt__s u-mb__s">{{ errors.email[0] }}</span>
                <input v-model="formData.email" id="email" type="email" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">

                <!-- メール送信ボタン -->
                <button type="submit" class="c-button c-button__submit c-button__user u-pd__s u-mt__m">パスワード変更メールを送信する</button>

            </form>
        </section>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
export default {
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
        async sendResetLink() {
            try {
                // 利用者パスワード変更メール送信APIをPOST送信
                await axios.post('/api/user/password/email', this.formData); // formDataを含めたリクエスト
                console.log('パスワード変更メールを送信します。');
                this.message = response.data.message;
                console.log('this.messageは、', this.message);
            } catch (error) {
                console.log('メール送信失敗：', error.response.data);
                this.errors = error.response.data.errors;
            }
        }
    }
};
</script>