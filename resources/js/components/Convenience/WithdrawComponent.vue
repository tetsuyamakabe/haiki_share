<template>
    <main class="l-main">
        <section class="l-main__wrapper">
            <h1 class="c-title u-mb__xl">コンビニ退会</h1>
            <form class="c-form">

                <h3 class="c-title c-title__sub">退会手続きを行いますか？</h3>
                <div class="p-article">
                    <p class="c-text__withdraw u-mt__m u-mb__m">
                        退会手続き前に必ずご確認ください。退会しますとすべての登録情報が削除され、会員様向けのサービスをご利用いただけなくなります。
                    </p>
                </div>
                <!-- 退会ボタン -->
                <button @click="withdraw" class="c-button c-button__submit c-button__convenience u-pd__s u-mt__m">退会する</button>

            </form>
        </section>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
export default {
    methods: {
        // 退会処理をサーバー側に送信するメソッド
        withdraw() {
            // コンビニ退会APIをDELETE送信
            axios.delete('/api/convenience/mypage/withdraw').then(response => {
                this.message = response.data.message;
                console.log('this.messageは、', this.message);
                console.log('退会します');
                this.$router.push({ name: 'top' }); // 退会処理完了後、TOP画面に遷移
            }).catch(error => {
                console.error('退会処理失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        }
    }
}
</script>