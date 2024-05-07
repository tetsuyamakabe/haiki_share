<template>
    <main class="l-main">
        <div class="p-register">
            <div class="p-register__form">
                <h1 class="c-title">パスワード変更メール送信</h1>
                <div class="p-container">
                    <form @submit.prevent="sendResetLink">

                        <!-- バリデーションエラーメッセージ -->
                        <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>

                        <table>
                            <tr>
                                <th><label for="email" class="c-label">メールアドレス</label></th>
                                <td>
                                    <input v-model="formData.email" id="email" type="email" class="c-input" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">
                                </td>
                            </tr>
                        </table>
                        <div class="p-forgotpassword__button">
                            <button type="submit" class="c-button">送信する</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <a @click="$router.back()">前のページに戻る</a>
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
            axios.post('/api/user/password/email', { email: this.formData.email }).then(response => {
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