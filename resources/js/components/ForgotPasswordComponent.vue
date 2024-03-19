<template>
    <div class="c-container">
        <form @submit.prevent="sendResetLink">
            <table>
                <tr>
                    <th><label for="email" class="c-label">メールアドレス</label></th>
                    <td>
                        <input v-model="formData.email" id="email" type="email" class="c-input" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">
                        <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
                    </td>
                </tr>
            </table>
            <div class="p-forgotpassword__button">
                <button type="submit" class="c-button">送信する</button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            formData: {
            email: '',
            },
            validationErrors: {},
            message: '',
            errors: {}
        };
    },

    methods: {
        sendResetLink() {
            // パスワードリセットリンク送信処理を実装する
            axios.post('/password/email', { email: this.formData.email })
            .then(response => {
                this.message = response.data.message;
                this.error = '';
            })
            .catch(error => {
                this.validationErrors = error.response.data.errors;
            });
        }
    }
};
</script>