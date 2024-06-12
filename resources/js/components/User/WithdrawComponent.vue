<template>
    <main class="l-main">
        <div class="l-main__header">
            <h1 class="c-title">利用者退会</h1>
        </div>
        <section class="l-main__wrapper">
            <!-- フラッシュメッセージを表示 -->
            <Toast />
            <form class="c-form">
                <h3 class="c-title c-title--sub">退会手続きを行いますか？</h3>
                <div class="p-article">
                    <p class="c-text--alert">
                        退会手続き前に必ずご確認ください。退会しますとすべての登録情報が削除され、会員様向けのサービスをご利用いただけなくなります。
                    </p>
                </div>
                <!-- 退会ボタン -->
                <button @click="withdraw" class="c-button c-button--submit c-button--main">退会する</button>
            </form>
        </section>
    </main>
</template>

<script>
import Toast from '../Toast.vue'; // Toastコンポーネントをインポート

export default {
    components: {
        Toast, // Toastコンポーネントを読み込み
    },

    computed: {
        // ログインユーザーかどうか
        isLogin() {
            return this.$store.getters['auth/check'];
        },
    },

    methods: {
        // 退会処理をサーバー側に送信するメソッド
        withdraw() {
            // 利用者退会APIをDELETE送信
            axios.delete('/api/user/mypage/withdraw').then(response => {
                this.$store.dispatch('flash/setFlashMessage', { // フラッシュメッセージの表示
                    message: '退会しました。',
                    type: 'success'
                });
                this.$router.push({ name: 'top' }); // 退会処理完了後、TOP画面に遷移
            }).catch(error => {
                this.errors = error.response.data.errors;
            });
        }
    }
}
</script>