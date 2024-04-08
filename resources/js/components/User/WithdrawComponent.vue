<template>
    <div class="p-container">
        <p>退会手続きを行いますか？</p>

        <!-- 退会ボタン -->
        <div class="p-withdraw__button">
            <button class="c-button" @click="withdraw">退会する</button>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user'],
    methods: {
        // 退会処理をサーバー側に送信するメソッド
        withdraw() {
            const userId = this.user.id;
            axios.post('/user/mypage/withdraw/' + userId).then(response => {
                console.log('退会が完了しました');
                window.location.href = '/home';
            }).catch(error => {
                console.error('退会処理失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        }
    }
}
</script>