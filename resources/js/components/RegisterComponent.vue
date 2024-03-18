<template>
    <div class="c-container">
        <form @submit.prevent="submitForm">
            <table>
                <tr>
                    <th><label for="name" class="c-label">お名前</label></th>
                    <td>
                        <input v-model="formData.name" id="name" type="name" class="c-input" :class="{ 'is-invalid': errors && errors.name }" autocomplete="name">
                        <span v-if="errors && errors.name" class="c-error">{{ errors.name[0] }}</span>
                    </td>
                </tr>

                <tr>
                    <th><label for="email" class="c-label">メールアドレス</label></th>
                    <td>
                        <input v-model="formData.email" id="email" type="email" class="c-input" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">
                        <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
                    </td>
                </tr>
                <tr>
                    <th><label for="password" class="c-label">パスワード</label></th>
                    <td>
                        <input v-model="formData.password" id="password" type="password" class="c-input" :class="{ 'is-invalid': errors && errors.password }" autocomplete="new-password" placeholder="英数字8文字以上で入力してください">
                        <span v-if="errors && errors.password" class="c-error">{{ errors.password[0] }}</span>
                    </td>
                </tr>
                <tr>
                    <th><label for="password-confirm" class="c-label">パスワード（再入力）</label></th>
                    <td>
                        <input v-model="formData.password_confirmation" id="password-confirm" type="password" class="c-input" :class="{ 'is-invalid': errors && errors.password_confirmation }" autocomplete="new-password" placeholder="英数字8文字以上で入力してください">
                        <span v-if="errors && errors.password_confirmation" class="c-error">{{ errors.password_confirmation[0] }}</span>
                    </td>
                </tr>
            </table>

            <div class="p-register__button">
                <button type="submit" class="c-button">登録する</button>
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
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            errors: null // エラーオブジェクトを初期化
        };
    },
    methods: {
        submitForm() {
            axios.post('/register', this.formData)
                .then(response => {
                    // 登録成功時の処理
                    // 例えば、リダイレクトなど
                    window.location.href = '/home'; // ホーム画面にリダイレクトする例
                })
                .catch(error => {
                    // console.error('ログイン失敗:', error.response.data);
                    this.errors = error.response.data.errors;
                });
        }
    }
}
</script>