<template>
    <!-- サイドバー -->
    <section class="l-sidebar">
        <div class="p-mypage__sidebar">
            <!-- プロフィール情報の表示 -->
            <div class="p-mypage__user-info u-pd__s">
                <h2 class="c-title c-title__sub u-mt__m u-mb__m">プロフィール情報</h2>
                <div class="p-mypage__profile u-pd__s">
                    <div class="p-mypage__avatar--container">
                        <img :src="avatar" alt="顔写真画像" class="p-mypage__avatar"> <!-- 顔写真 -->
                    </div>
                    <div class="p-mypage__profile--header u-mt__s">
                        <p>{{ username }}</p> <!-- お名前 -->
                    </div>
                    <div class="p-mypage__profile--container">
                        <!-- 自己紹介文（ない場合は非表示） -->
                        <p v-if="introduction" class="p-mypage__profile--content u-m__m"><i class="fa-solid fa-message"></i>{{ introduction }}</p> 
                    </div>
                    <!-- プロフィール編集ボタン -->
                    <div class="p-mypage__profile--footer c-button__container">
                        <router-link class="c-button c-button__primary u-pd__s u-m__s" :to="{ name: 'user.profile' }">プロフィール編集</router-link>
                    </div>
                </div>
            </div>
            <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'products' }">商品一覧</router-link>
            <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'user.products.purchased' }">購入した商品一覧</router-link>
            <router-link class="c-link u-mt__xl u-mb__xl" :to="{ name: 'user.products.liked' }">お気に入り登録した商品一覧</router-link>
        </div>
    </section>
</template>

<script>
export default {
    props: ['introduction'],

    computed: {
        // 顔写真を表示する
        avatar() {
            return this.$store.getters['auth/avatar'];
        },

        // ユーザーの名前を表示する
        username() {
            return this.$store.getters['auth/username'];
        },
    },
}
</script>