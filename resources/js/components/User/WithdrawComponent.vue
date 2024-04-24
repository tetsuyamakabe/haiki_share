<template>
    <main class="l-main">
        <div class="p-profile">
            <div class="p-profile__form">
                <h1 class="c-title">退会</h1>
                <div class="p-container">
                    <p>退会手続きを行いますか？</p>
                    <!-- 退会ボタン -->
                    <div class="p-withdraw__button">
                        <button class="c-button" @click="withdraw">退会する</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    created() {
        this.userId = this.$route.params.userId; // ルートからuserIdを取得
    },

    methods: {
        // 退会処理をサーバー側に送信するメソッド
        withdraw() {
            const userId = this.userId;
            axios.delete('/api/user/mypage/withdraw/' + userId).then(response => {
                this.message = response.data.message;
                console.log('this.messageは、', this.message);
                console.log('退会します');
                this.$router.push({ name: 'home' }); // 退会処理完了後、HOME画面に遷移
            }).catch(error => {
                console.error('退会処理失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        }
    }
}
</script>