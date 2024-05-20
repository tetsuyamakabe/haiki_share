<template>
    <main class="l-main">
        <section class="l-main__wrapper">
            <h1 class="c-title u-mb__xl">お問い合わせ</h1>
            <form @submit.prevent="submitForm" class="c-form">
                <!-- フラッシュメッセージ -->
                <div v-if="flashMessage" class="c-flash">{{ flashMessage }}</div>

                <!-- バリデーションエラーメッセージ -->
                <span v-if="errors && errors.name" class="c-error u-mt__s u-mb__s">{{ errors.name[0] }}</span>
                <span v-if="errors && errors.email" class="c-error u-mt__s u-mb__s">{{ errors.email[0] }}</span>
                <span v-if="errors && errors.contact" class="c-error u-mt__s u-mb__s">{{ errors.contact[0] }}</span>

                <!-- お名前 -->
                <label for="name" class="c-label">お名前</label>
                <input v-model="formData.name" id="name" type="name" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.name }" autocomplete="name">
                <!-- メールアドレス -->
                <label for="email" class="c-label">メールアドレス</label>
                <input v-model="formData.email" id="email" type="email" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">
                <!--お問い合わせ内容 -->
                <label for="contact" class="c-label">お問い合わせ内容</label>
                <textarea v-model="formData.contact" id="contact" type="text" class="c-textarea u-pd__s u-mt__m u-mb__m" cols="30" rows="10" :class="{ 'is-invalid': errors && errors.contact }"></textarea>

                <!-- 送信ボタン -->
                <button type="submit" class="c-button c-button__submit c-button__user u-pd__s u-mt__m">送信する</button>

            </form>
        </section>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            formData: {
                name: '',
                email: '',
                contact: '',
            },
            errors: null,
            flashMessage: '',
        };
    },

    methods: {
        // 入力された値をサーバー側に送信するメソッド
        submitForm() {
            axios.post('/api/contact', this.formData).then(response => {
                console.log('お問い合わせ内容を送信します。');
                this.flashMessage = 'お問い合わせ受付完了メールを送信しました。';
            }).catch(error => {
                console.log('errorは、', error);
                console.error('ユーザー登録失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        },
    }
}
</script>
