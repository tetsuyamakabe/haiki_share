<template>
    <section class="l-article__sidebar">
        <div class="p-sidebar">
            <h2 class="c-title c-title--sub">プロフィール情報</h2>
            <div class="p-sidebar__profile">
                <div class="p-sidebar__avatar-wrap">
                    <div class="p-sidebar__avatar">
                        <img :src="avatar" alt="顔写真画像" class="p-sidebar__avatar--img">
                    </div>
                </div>
                <div class="p-sidebar__content-wrap">
                    <div class="p-sidebar--header">
                        <p>{{ convenience_name }}{{ branch_name }}</p>
                    </div>
                    <div class="p-sidebar--body">
                        <p class="p-sidebar--content">
                            <i class="fa-solid fa-location-dot"></i>{{ prefecture }}{{ city }}{{ town }}{{ building }}
                        </p>
                        <p v-if="introduction" class="p-sidebar--content">
                            <i class="fa-solid fa-message"></i>{{ introduction }}
                        </p>
                    </div>
                    <div class="p-sidebar--footer">
                        <router-link class="c-button c-button--primary" :to="{ name: 'convenience.profile' }">プロフィール編集</router-link>
                    </div>
                </div>
            </div>
            <div class="c-button__container">
                <router-link class="c-button c-button--main u-m__m" :to="{ name: 'convenience.products.create' }">商品出品する</router-link>
            </div>
            <router-link class="p-sidebar--link c-link" :to="{ name: 'products' }">商品一覧</router-link>
            <router-link class="p-sidebar--link c-link" :to="{ name: 'convenience.products.sale' }">出品した商品一覧</router-link>
            <router-link class="p-sidebar--link c-link" :to="{ name: 'convenience.products.purchase' }">購入された商品一覧</router-link>
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
        avatar() {
            return this.$store.getters['auth/avatar'];
        },
    },
}
</script>