<template>
    <header class="l-header js-float-menu">
        <div class="l-header__inner">
            <router-link class="c-header__title" :to="{ name: 'home' }">
                haiki share
            </router-link>
            <div class="c-nav__menu--trigger" @click="open = !open">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <nav class="c-nav__menu" v-bind:class="{'is-active' : open }">
                <ul class="c-nav__menu--list">
                    <div v-if="isLogin" class="navbar__item">
                        {{ username }}
                        <RouterLink :to="userLink">マイページ</RouterLink>
                        <RouterLink :to="logoutLink" @click="logout">ログアウト</RouterLink>
                    </div>
                    <div v-else class="navbar__item">
                        <RouterLink class="button button--link" to="/user/register">利用者ユーザー登録</RouterLink>
                        <RouterLink class="button button--link" to="/user/login">利用者ログイン</RouterLink>
                        <RouterLink class="button button--link" to="/convenience/register">コンビニユーザー登録</RouterLink>
                        <RouterLink class="button button--link" to="/convenience/login">コンビニログイン</RouterLink>
                    </div>
                </ul>
            </nav>
        </div>
    </header>
</template>

<script>
export default {
    data() {
        return {
            open: false,
        }
    },

    computed: {
        // ログインユーザーかどうか
        isLogin() {
            return this.$store.getters['auth/check'];
        },

        // ユーザーの名前を表示する
        username() {
            return this.$store.getters['auth/username'];
        },

        // ログインユーザーのroleによって利用者・コンビニのマイページリンクを動的に変える
        userLink() {
            if (this.$store.getters['auth/role'] === 'user') {
                return "/user/mypage";
            } else if (this.$store.getters['auth/role'] === 'convenience') {
                return "/convenience/mypage";
            }
            return "/home";
        },

        // ログインユーザーのroleによって利用者・コンビニのログアウトリンクを動的に変える
        logoutLink() {
            if (this.$store.getters['auth/role'] === 'user') {
                return "/user/logout";
            } else if (this.$store.getters['auth/role'] === 'convenience') {
                return "/convenience/logout";
            }
            return "/home";
        },
    },

    methods: {
        // ログアウト処理
        logout() {
            // roleによってログアウトのAPIのエンドポイントとログアウト後のログイン画面のルートを動的に変える
            const logoutEndpoint = this.$store.getters['auth/role'] === 'user' ? '/api/user/logout' : '/api/convenience/logout';
            const loginRoute = this.$store.getters['auth/role'] === 'user' ? 'user.login' : 'convenience.login';

            axios.post(logoutEndpoint).then(response => {
                console.log('ログアウト成功:', response.data.message);
                this.$store.commit('auth/clearUser');
                this.$router.push({ name: loginRoute }); // ログアウト処理完了後、ログイン画面に遷移
            }).catch(error => {
                console.error('ログアウト処理失敗:', error.response.data);
            });
        }
    }
};
</script>