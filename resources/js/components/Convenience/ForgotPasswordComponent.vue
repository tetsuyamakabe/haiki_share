<template>
    <main class="l-main">
        <div class="l-main__convenience">
            <section class="l-main__wrapper">
                <h1 class="c-title u-mb__xl">コンビニパスワード変更メール送信</h1>
                <form @submit.prevent="sendResetLink" class="c-form">

                    <!-- バリデーションエラーメッセージ -->
                    <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>

                    <!-- メールアドレス -->
                    <label for="email" class="c-label">メールアドレス</label>
                    <input v-model="formData.email" id="email" type="email" class="c-input" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">

                    <!-- メール送信ボタン -->
                    <button type="submit" class="c-button c-button__submit c-button__convenience u-mt__m">パスワード変更メールを送信する</button>

                </form>
            </section>
        </div>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
export default {
    data() {
        return {
            formData: {
                email: '',
            },
            errors: null
        };
    },

    methods: {
        // パスワードリセットメール送信処理
        sendResetLink() {
            axios.post('/api/convenience/password/email', { email: this.formData.email }).then(response => {
                console.log('パスワード変更メールを送信します。');
                this.message = response.data.message;
                console.log('this.messageは、', this.message);
            }).catch(error => {
                console.log('メール送信失敗：', error.response.data);
                this.errors = error.response.data.errors;
            });
        }
    }
};
</script>