<template>
    <div class="c-container">
        <form @submit.prevent="resetPassword">
            <table>
                <tr>
                    <th><label for="old_password" class="c-label">古いパスワード</label></th>
                    <td>
                        <input v-model="oldPassword" id="old_password" type="password" class="c-input" :class="{ 'is-invalid': errors && errors.oldPassword }" autocomplete="password">
                        <span v-if="errors && errors.oldPassword" class="c-error">{{ errors.oldPassword[0] }}</span>
                    </td>
                </tr>
                <tr>
                    <th><label for="new_password" class="c-label">新しいパスワード</label></th>
                    <td>
                        <input v-model="newPassword" id="new_password" type="password" class="c-input" :class="{ 'is-invalid': errors && errors.newPassword }" autocomplete="password">
                        <span v-if="errors && errors.newPassword" class="c-error">{{ errors.newPassword[0] }}</span>
                    </td>
                </tr>
                <tr>
                    <th><label for="password-confirm" class="c-label">新しいパスワード（再入力）</label></th>
                    <td>
                        <input v-model="password_confirmation" id="password_confirm" type="password" class="c-input" :class="{ 'is-invalid': errors && errors.password_confirmation }" autocomplete="password">
                        <span v-if="errors && errors.password_confirmation" class="c-error">{{ errors.password_confirmation[0] }}</span>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="token" v-model="token">
            <div class="p-resetpassword__button">
                <button type="submit" class="c-button">変更する</button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            oldPassword: '',
            newPassword: '',
            password_confirmation: '',
            token: '',
            email: '',
            errors: null
        };
    },

    mounted() {
        const params = new URLSearchParams(window.location.search);
        this.email = params.get('email');
        this.token = params.get('token');
        console.log('Email:', this.email); 
        console.log('Token:', this.token);
    },

    methods: {
        resetPassword() {
            if (this.newPassword !== this.password_confirmation) {
                this.error = "Passwords do not match";
                return;
            }
            axios.post('/convenience/password/reset', {
                email: this.email,
                password: this.newPassword,
                password_confirmation: this.password_confirmation,
                token: this.token
            })
            .then(response => {
                this.message = response.data.message;
                this.error = '';
                // パスワードがリセットされた後にログイン画面に遷移する
                window.location.href = '/login';
            })
            .catch(error => {
                console.log(error.response.data);
                this.errors = error.response.data.errors;
                this.message = '';
            });
        }
    }
};
</script>