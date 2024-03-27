<template>
    <div class="c-container">
        <form @submit.prevent="submitForm">

            <!-- バリデーションエラーメッセージ -->
            <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
            <span v-if="errors && errors.password" class="c-error">{{ errors.password[0] }}</span>

            <table>
                <tr>
                    <th><label for="email" class="c-label">メールアドレス</label></th>
                    <td>
                        <input v-model="formData.email" id="email" type="email" class="c-input" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">
                    </td>
                </tr>
                <tr>
                    <th><label for="password" class="c-label">パスワード</label></th>
                    <td>
                        <input v-model="formData.password" id="password" type="password" class="c-input" :class="{ 'is-invalid': errors && errors.password }" autocomplete="new-password" placeholder="英数字8文字以上で入力してください">
                    </td>
                </tr>
            </table>
            <div class="p-login__button">
                <button type="submit" class="c-button">ログイン</button>
            </div>
        </form>
    </div>
</template>
  
<script>
import axios from 'axios';

export default {
    data() {
        return {
            formData: {
                email: '',
                password: '',
                remember: false
            },
            errors: null
        };
    },
    methods: {
        submitForm() {
            axios.post('/login', this.formData).then(response => {
                // 登録成功時の処理
                // 例えば、リダイレクトなど
                window.location.href = '/home'; // ホーム画面にリダイレクトする例
            }).catch(error => {
                console.error('ログイン失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        }
    }
};
</script>