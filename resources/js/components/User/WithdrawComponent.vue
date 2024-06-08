<template>
    <main class="l-main">
        <section class="l-main__wrapper">
            <h1 class="c-title u-mt__xl u-mb__xl">利用者退会</h1>
            <!-- フラッシュメッセージを表示 -->
            <Toast />

            <form class="c-form">

                <h3 class="c-title c-title__sub">退会手続きを行いますか？</h3>
                <div class="p-article">
                    <p class="c-text__withdraw u-mt__m u-mb__m">
                        退会手続き前に必ずご確認ください。退会しますとすべての登録情報が削除され、会員様向けのサービスをご利用いただけなくなります。
                    </p>
                </div>
                <!-- 退会ボタン -->
                <button @click="withdraw" class="c-button c-button__submit c-button__main u-pd__s u-mt__m">退会する</button>

            </form>
        </section>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
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
                this.$store.dispatch('flash/setFlashMessage', { // フラッシュメッセージの表示
                    message: '退会できませんでした。',
                    type: 'error'
                });
                console.error('退会処理失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        }
    }
}
</script>