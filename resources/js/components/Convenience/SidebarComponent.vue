<template>
    <!-- サイドバー -->
    <section class="l-sidebar">
        <div class="p-mypage__sidebar">
            <!-- プロフィール情報の表示 -->
            <div class="p-mypage__user-info u-pd__s">
                <h2 class="c-title c-title__sub u-mt__m u-mb__m">プロフィール情報</h2>
                <div class="p-mypage__profile u-pd__s">
                    <div class="p-mypage__icon--container">
                        <img :src="icon" alt="顔写真画像" class="p-mypage__icon"> <!-- 顔写真 -->
                    </div>
                    <div class="p-mypage__profile--header u-mt__s">
                        <p>{{ convenience_name }}{{ branch_name }}</p> <!-- コンビニ名・支店名 -->
                    </div>
                    <div class="p-mypage__profile--container">
                        <!-- 住所 -->
                        <p class="p-mypage__profile--content u-m__m"><i class="fa-solid fa-location-dot"></i>{{ prefecture }}{{ city }}{{ town }}{{ building }}</p> 
                        <!-- 自己紹介文（ない場合は非表示） -->
                        <p v-if="introduction" class="p-mypage__profile--content u-m__m"><i class="fa-solid fa-message"></i>{{ introduction }}</p> 
                    </div>
                    <!-- プロフィール編集ボタン -->
                    <div class="p-mypage__profile--footer c-button__container">
                        <router-link class="c-button c-button__primary u-pd__s u-m__s" :to="{ name: 'convenience.profile' }">プロフィール編集</router-link>
                    </div>
                </div>
            </div>
            <div class="c-button__container">
                <router-link class="c-button c-button__main u-pd__s u-m__l" :to="{ name: 'convenience.products.create' }">商品出品する</router-link>
            </div>
            <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'convenience.products.sale' }">出品した商品一覧</router-link>
            <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'convenience.products.purchase' }">購入された商品一覧</router-link>
            <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'products' }">商品一覧</router-link>
        </div>
    </section>
</template>

<script>
export default {
    props: ['convenience_name', 'branch_name', 'prefecture', 'city', 'town', 'building', 'introduction'],

    computed: {
        // ログインユーザーかどうか
        isLogin() {
            return this.$store.getters['auth/check'];
        },

        // 顔写真を表示する
        icon() {
            return this.$store.getters['auth/icon'];
        },
    },
}
</script>