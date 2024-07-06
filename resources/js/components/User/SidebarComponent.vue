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
                        <p class="c-sidebar__username">{{ username }}</p> <!-- お名前 -->
                    </div>
                    <div class="c-sidebar__body">
                        <div v-if="introduction" class="c-sidebar--content">
                            <i class="fa-solid fa-message"></i>
                            <p class="c-sidebar__introduction">{{ introduction }}</p><!-- 自己紹介文（ない場合は非表示） -->
                        </div>
                    </div>
                    <div class="c-sidebar__footer">
                        <router-link class="c-button c-button--primary" :to="{ name: 'user.profile' }">プロフィール編集</router-link>
                    </div>
                </div>
            </div>
            <router-link class="c-sidebar--link c-link" :to="{ name: 'products' }">商品一覧</router-link>
            <router-link class="c-sidebar--link c-link" :to="{ name: 'user.products.purchased' }">購入した商品一覧</router-link>
            <router-link class="c-sidebar--link c-link" :to="{ name: 'user.products.liked' }">お気に入り商品一覧</router-link>
        </div>
    </section>
</template>

<script>
export default {
    props: ['introduction'],  // 親コンポーネントからプロフィール情報を受け取る

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