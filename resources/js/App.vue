<template>
    <div>
        <HeaderComponent />
        <main>
            <div class="container">
                <RouterView />
            </div>
        </main>
        <FooterComponent />
    </div>
</template>

<script>
import HeaderComponent from './components/HeaderComponent.vue'
import FooterComponent from './components/FooterComponent.vue'
import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR, UNPROCESSABLE_ENTITY } from './util'

export default {
    components: {
        HeaderComponent,
        FooterComponent
    },

    computed: {
        errorCode () {
            return this.$store.state.error.code
        }
    },

    watch: {
        errorCode: {
            async handler (val) {
                if (val === INTERNAL_SERVER_ERROR) {
                    this.$router.push('/500')
                } else if (val === UNAUTHORIZED) {
                    // トークンをリフレッシュ
                    await axios.get('/api/refresh-token')
                    // ストアのuserをクリア
                    this.$store.commit('auth/setUser', null)
                    // HOME画面へ
                    this.$router.push('/home')
                } else if (val === NOT_FOUND) {
                    this.$router.push('/404')
                } else if (val === UNPROCESSABLE_ENTITY) {
                    // バリデーションエラーメッセージの表示
                    this.errors = error.response.data.errors;
                }
            },
            immediate: true
        },

        $route () {
            this.$store.commit('error/setCode', null)
        }
    }
}
</script>