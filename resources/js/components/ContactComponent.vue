<template>
    <main class="l-main">
        <div class="l-main__header">
            <h1 class="c-title">お問い合わせ</h1>
        </div>
        <section class="l-main__wrapper">
            <!-- フラッシュメッセージを表示 -->
            <Toast />
            <form @submit.prevent="submitForm" class="c-form">
                <!-- お名前 -->
                <label for="name" class="c-label">お名前<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.name" class="c-error">{{ errors.name[0] }}</span>
                <input v-model="formData.name" id="name" type="name" class="c-input" :class="{ 'is-invalid': errors && errors.name }" autocomplete="name">
                <!-- メールアドレス -->
                <label for="email" class="c-label">メールアドレス<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
                <input v-model="formData.email" id="email" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">
                <!--お問い合わせ内容 -->
                <label for="contact" class="c-label">お問い合わせ内容<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.contact" class="c-error">{{ errors.contact[0] }}</span>
                <textarea v-model="formData.contact" maxlength="255" id="contact" type="text" class="c-textarea" cols="30" rows="10" @keyup="countCharacters" :class="{ 'is-invalid': errors && errors.contact }"></textarea>
                <span class="c-textarea--count">{{ formData.contact.length }} / 255文字</span>
                <!-- 送信ボタン -->
                <button type="submit" class="c-button c-button--submit c-button--main">お問い合わせ内容を送信する</button>

            </form>
        </section>
    </main>
</template>

<script>
import Toast from './Toast.vue'; // Toastコンポーネントをインポート

export default {
    components: {
        Toast, // Toastコンポーネントを読み込み
    },

    data() {
        return {
            formData: {
                name: '', // お名前
                email: '', // メールアドレス
                contact: '', // お問い合わせ内容
            },
            errors: null, // エラーメッセージ
        };
    },

    methods: {
        // 入力された値をサーバー側に送信するメソッド
        submitForm() {
            // お問い合わせ送信APIをPOST送信
            axios.post('/api/contact', this.formData).then(response => {
                this.$store.dispatch('flash/setFlashMessage', { // フラッシュメッセージの表示
                    message: 'お問い合わせ内容を受け付けました。',
                    type: 'success'
                });
            }).catch(error => {
                this.errors = error.response.data.errors;
            });
        },

        // お問い合わせ内容の文字数をカウントするメソッド
        countCharacters() {
            this.countCharactersLength = this.formData.contact.length; // 文字数のカウント
            if (this.countCharactersLength > 255) { // 文字数が50文字を超えているか
                this.formData.count = this.formData.count.slice(0, 255); // 文字列を255文字まで切り抜く
                this.countCharactersLength = 255; // 255文字以上の文字数を制限する
            }
        },
    }
}
</script>
