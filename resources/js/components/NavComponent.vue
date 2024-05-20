<template>
    <div class="l-nav">
        <div class="l-nav__menu--trigger" @click="toggleMenu" v-bind:class="{'is-active' : open }">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <nav class="l-nav__menu" v-bind:class="{'is-active' : open }">
            <ul class="l-nav__menu--list">
                <!-- ログイン済みのナビゲーションメニュー -->
                <img v-if="isLogin" :src="icon" alt="アイコン画像" class="l-nav__menu--icon u-mr__s">
                <div v-if="isLogin" class="l-nav__menu--item">
                    <span class="l-nav__menu--name">{{ username }}</span>
                    <RouterLink class="l-nav__menu--link" :to="userLink" @click.native="closeMenu">マイページ</RouterLink>
                    <a class="l-nav__menu--link" @click="logout">ログアウト</a>
                </div>
                <!-- HOME画面のナビゲーションメニュー -->
                <div v-else-if="isHomePage" class="l-nav__menu--item">
                    <a class="l-nav__menu--link" href="#about" @click="closeMenu">サービス概要</a>
                    <a class="l-nav__menu--link" href="#merit" @click="closeMenu">メリット</a>
                    <a class="l-nav__menu--link" href="#usage" @click="closeMenu">利用方法</a>
                    <a class="l-nav__menu--link" href="#contact" @click="closeMenu">お問い合わせ</a>
                    <RouterLink class="l-nav__menu--link" to="/user/register" @click.native="closeMenu">利用者ユーザー登録</RouterLink>
                    <RouterLink class="l-nav__menu--link" to="/convenience/register" @click.native="closeMenu">コンビニユーザー登録</RouterLink>
                </div>
                <!-- ログインしていないHOME画面以外のナビゲーションメニュー -->
                <div v-else class="l-nav__menu--item">
                    <RouterLink class="l-nav__menu--link" to="/user/register" @click.native="closeMenu">利用者ユーザー登録</RouterLink>
                    <RouterLink class="l-nav__menu--link" to="/convenience/register" @click.native="closeMenu">コンビニユーザー登録</RouterLink>
                    <RouterLink class="l-nav__menu--link" to="/user/login" @click.native="closeMenu">利用者ログイン</RouterLink>
                    <RouterLink class="l-nav__menu--link" to="/convenience/login" @click.native="closeMenu">コンビニログイン</RouterLink>
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

        // トップページかどうか
        isHomePage() {
            return this.$route.name === 'home';
        }
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