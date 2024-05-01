<template>
    <div class="p-login__button">
        <button @click="logout" class="c-button">ログアウト</button>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    methods: {
        // ログアウト処理
        logout() {
            axios.post('/api/user/logout').then(response => {
                this.message = response.data.message;
                console.log('this.messageは、', this.message);
                console.log('ログアウトします');
                this.$store.commit('auth/clearUser');
                this.$router.push({ name: 'user.login' }); // ログアウト処理完了後、利用者側ログイン画面に遷移
            }).catch(error => {
                console.error('ログアウト処理失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        }
    }
}
</script>