<template>
    <nav class="l-nav">
        <div class="l-nav__trigger" @click="toggleMenu" v-bind:class="{'is-active' : open }">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul class="l-nav__menu" :class="{ 'is-active': open }">
            <li v-if="isLogin" class="l-nav__item">
                <!-- ログイン済みのナビゲーションメニュー -->
                <RouterLink class="l-nav__link" :to="userLink" @click.native="closeMenu">マイページ</RouterLink>
                <a class="l-nav__link" @click="logout">ログアウト</a>
            </li>
            <li v-else-if="isTopPage" class="l-nav__item">
                <!-- TOP画面のナビゲーションメニュー -->
                <a class="l-nav__link" href="#about" @click="closeMenu">サービス概要</a>
                <a class="l-nav__link" href="#merit" @click="closeMenu">メリット</a>
                <a class="l-nav__link" href="#usage" @click="closeMenu">利用方法</a>
                <a class="l-nav__link" href="#contact" @click="closeMenu">お問い合わせ</a>
                <RouterLink class="l-nav__link" to="/user/login" @click.native="closeMenu">利用者ログイン</RouterLink>
                <RouterLink class="l-nav__link" to="/convenience/login" @click.native="closeMenu">コンビニログイン</RouterLink>
            </li>
            <li v-else class="l-nav__item">
                <!-- ログインしていないTOP画面以外のナビゲーションメニュー -->
                <RouterLink class="l-nav__link" to="/user/register" @click.native="closeMenu">利用者ユーザー登録</RouterLink>
                <RouterLink class="l-nav__link" to="/convenience/register" @click.native="closeMenu">コンビニユーザー登録</RouterLink>
                <RouterLink class="l-nav__link" to="/user/login" @click.native="closeMenu">利用者ログイン</RouterLink>
                <RouterLink class="l-nav__link" to="/convenience/login" @click.native="closeMenu">コンビニログイン</RouterLink>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    data() {
        return {
            open: false, // ハンバーガーメニュー
        }
    },

    computed: {
        // ログインユーザーかどうか
        isLogin() {
            return this.$store.getters['auth/check'];
        },

        // ログインユーザーのroleによって利用者・コンビニのマイページリンクを動的に変える
        userLink() {
            if (this.$store.getters['auth/role'] === 'user') {
                return "/user/mypage";
            } else if (this.$store.getters['auth/role'] === 'convenience') {
                return "/convenience/mypage";
            }
            return "/top";
        },

        // トップページかどうか
        isTopPage() {
            return this.$route.name === 'top';
        }
    },

    methods: {
        // ログアウト処理
        logout() {
            // roleによってログアウトのAPIのエンドポイントとログアウト後のログイン画面のルートを動的に変える
            const logoutEndpoint = this.$store.getters['auth/role'] === 'user' ? '/api/user/logout' : '/api/convenience/logout';
            const loginRoute = this.$store.getters['auth/role'] === 'user' ? 'user.login' : 'convenience.login';
            axios.post(logoutEndpoint).then(response => {
                this.$store.commit('auth/clearUser');
                this.closeMenu(); // メニューを閉じる
                this.$router.push({ name: loginRoute }); // ログアウト処理完了後、ログイン画面に遷移
            }).catch(error => {
                console.error('ログアウト処理失敗:', error.response.data);
            });
        },

        // ハンバーガーメニューの開閉
        toggleMenu() {
            this.open = !this.open;
        },

        // ハンバーガーメニュー中のメニューをクリックしたら自動で閉じる
        closeMenu() {
            this.open = false;
        }
    }
}
</script>