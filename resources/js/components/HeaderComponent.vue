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
                    <li class="c-nav__menu--item"><router-link class="c-nav__menu--link" :to="{ name: 'user.logout' }">利用者ログアウト</router-link></li>
                    <li class="c-nav__menu--item"><router-link class="c-nav__menu--link" :to="{ name: 'convenience.register' }">コンビニユーザー登録</router-link></li>
                    <li class="c-nav__menu--item"><router-link class="c-nav__menu--link" :to="{ name: 'convenience.login' }">コンビニログイン</router-link></li>
                    <li class="c-nav__menu--item"><router-link class="c-nav__menu--link" :to="{ name: 'convenience.logout' }">コンビニログアウト</router-link></li>
                </ul>
            </nav>
        </div>
    </header>
</template>

<script>
    export default {}
</script>