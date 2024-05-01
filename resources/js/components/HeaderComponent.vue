<template>
    <header class="l-header js-float-menu">
        <div class="l-header__inner">
            <router-link class="c-header__title" :to="{ name: 'home' }">
                haiki share
            </router-link>
            <div class="c-nav__menu--trigger js-toggle-sp-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <nav class="c-nav__menu js-toggle-sp-menu-target">
                <ul class="c-nav__menu--list">
                    <li class="c-nav__menu--item"><router-link class="c-nav__menu--link" :to="{ name: 'user.register' }">利用者ユーザー登録</router-link></li>
                    <li class="c-nav__menu--item"><router-link class="c-nav__menu--link" :to="{ name: 'user.login' }">利用者ログイン</router-link></li>
                    <li class="c-nav__menu--item"><button @click="userLogout" class="c-nav__menu--link">利用者ログアウト</button></li>
                    <li class="c-nav__menu--item"><router-link class="c-nav__menu--link" :to="{ name: 'convenience.register' }">コンビニユーザー登録</router-link></li>
                    <li class="c-nav__menu--item"><router-link class="c-nav__menu--link" :to="{ name: 'convenience.login' }">コンビニログイン</router-link></li>
                    <li class="c-nav__menu--item"><button @click="convenienceLogout" class="c-nav__menu--link">コンビニログアウト</button></li>
                </ul>
            </nav>
        </div>
    </header>
</template>

<script>
import axios from 'axios';

export default {
    methods: {
        // 利用者ログアウト処理
        userLogout() {
            axios.post('/api/user/logout').then(response => {
                this.message = response.data.message;
                console.log('this.messageは、', this.message);
                console.log('ログアウトします');
                this.$router.push({ name: 'user.login' }); // ログアウト処理完了後、利用者側ログイン画面に遷移
            }).catch(error => {
                console.error('ログアウト処理失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        },

        // コンビニログアウト処理
        convenienceLogout() {
            axios.post('/api/convenience/logout').then(response => {
                this.message = response.data.message;
                console.log('this.messageは、', this.message);
                console.log('ログアウトします');
                this.$router.push({ name: 'convenience.login' }); // ログアウト処理完了後、コンビニ側ログイン画面に遷移
            }).catch(error => {
                console.error('ログアウト処理失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        }

    }
}
</script>