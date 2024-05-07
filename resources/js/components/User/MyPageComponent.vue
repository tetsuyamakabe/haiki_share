<template>
    <main class="l-main">
        <div class="p-mypage">
            <div class="p-mypage__main">
                <h1 class="c-title">マイページ</h1>
                <div class="p-container">
                    <router-link :to="{ name: 'user.profile' }">プロフィール編集</router-link>
                    <router-link :to="{ name: 'user.withdraw' }">退会</router-link>
                    <router-link :to="{ name: 'products' }">商品一覧</router-link>
                </div>
            </div>
        </div>
        <a @click="$router.back()">前のページに戻る</a>
    </main>
</template>

<script>
    export default {}
</script>