<template>
    <main class="l-main">
        <div class="p-mypage">
            <div class="p-mypage__main">
                <h1 class="c-title">マイページ</h1>
                <div class="p-container">
                    <router-link :to="{ name: 'convenience.profile' }">プロフィール編集</router-link>
                    <router-link :to="{ name: 'convenience.withdraw' }">退会</router-link>
                    <router-link :to="{ name: 'convenience.products.create' }">商品出品</router-link>
                    <router-link :to="{ name: 'convenience.products.sale' }">出品した商品一覧</router-link>
                    <router-link :to="{ name: 'convenience.products.purchase' }">購入された商品一覧</router-link>
                    <router-link :to="{ name: 'products' }">商品一覧</router-link>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    export default {}
</script>