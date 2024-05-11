<template>
    <div class="l-nav">
        <div class="l-nav__menu--trigger" @click="open = !open" v-bind:class="{'is-active' : open }">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <nav class="l-nav__menu" v-bind:class="{'is-active' : open }">
            <ul class="l-nav__menu--list">
                <img v-if="isLogin" :src="icon" alt="アイコン画像" class="l-nav__menu--icon u-mr__s">
                <div v-if="isLogin" class="l-nav__menu--item">
                    <span class="l-nav__menu--name">{{ username }}</span>
                    <RouterLink class="l-nav__menu--link" :to="userLink">マイページ</RouterLink>
                    <RouterLink class="l-nav__menu--link" :to="logoutLink" @click="logout">ログアウト</RouterLink>
                </div>
                <div v-else class="l-nav__menu--item">
                    <RouterLink class="l-nav__menu--link" to="/user/register">利用者ユーザー登録</RouterLink>
                    <RouterLink class="l-nav__menu--link" to="/user/login">利用者ログイン</RouterLink>
                    <RouterLink class="l-nav__menu--link" to="/convenience/register">コンビニユーザー登録</RouterLink>
                    <RouterLink class="l-nav__menu--link" to="/convenience/login">コンビニログイン</RouterLink>
                </div>
            </ul>
        </nav>
    </div>
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

        // アイコンを表示する
        icon() {
            return this.$store.getters['auth/icon'];
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
}
</script>