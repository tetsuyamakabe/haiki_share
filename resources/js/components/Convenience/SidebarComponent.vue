<template>
    <section class="l-article__sidebar">
        <div class="p-sidebar">
            <h2 class="c-title c-title--sub">プロフィール情報</h2>
            <div class="c-sidebar">
                <div class="c-sidebar__avatar--wrap">
                    <div class="c-sidebar__avatar">
                        <img :src="avatar" alt="顔写真画像" class="c-sidebar__avatar--img"> <!-- 顔写真 -->
                    </div>
                </div>
                <div class="c-sidebar__content--wrap">
                    <div class="c-sidebar__header">
                        <p class="c-sidebar__username">{{ convenience_name }}{{ branch_name }}</p> <!-- コンビニ名・支店名 -->
                    </div>
                    <div class="c-sidebar__body">
                        <div class="c-sidebar--content">
                            <i class="fa-solid fa-location-dot"></i>
                            <span class="c-sidebar__address">{{ prefecture }}{{ city }}{{ town }}{{ building }}</span> <!-- 住所 -->
                        </div>
                        <div v-if="introduction" class="c-sidebar--content">
                            <i class="fa-solid fa-message"></i>
                            <p class="c-sidebar__introduction">{{ introduction }}</p> <!-- 自己紹介文（ない場合は非表示） -->
                        </div>
                    </div>
                    <div class="c-sidebar__footer">
                        <router-link class="c-button c-button--primary" :to="{ name: 'convenience.profile' }">プロフィール編集</router-link>
                    </div>
                </div>
            </div>
            <div class="c-button__container">
                <router-link class="c-button c-button--main" :to="{ name: 'convenience.products.create' }">商品出品する</router-link>
            </div>
            <router-link class="c-sidebar--link c-link" :to="{ name: 'products' }">商品一覧</router-link>
            <router-link class="c-sidebar--link c-link" :to="{ name: 'convenience.products.sale' }">出品した商品一覧</router-link>
            <router-link class="c-sidebar--link c-link" :to="{ name: 'convenience.products.purchase' }">購入された商品一覧</router-link>
        </div>
    </section>
</template>

<script>
export default {
    props: ['convenience_name', 'branch_name', 'prefecture', 'city', 'town', 'building', 'introduction'], // 親コンポーネントからプロフィール情報を受け取る

    computed: {
        // ログインユーザーかどうか
        isLogin() {
            return this.$store.getters['auth/check'];
        },

        // 顔写真を表示する
        avatar() {
            return this.$store.getters['auth/avatar'];
        },
    },
}
</script>