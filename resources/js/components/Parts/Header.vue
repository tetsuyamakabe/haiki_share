<template>
    <header class="l-header">
        <div class="l-header__inner">
            <router-link class="l-header__logo" :to="{ name: 'top' }">haiki share</router-link>
            <Nav />
        </div>
    </header>
</template>

<script>
import Nav from './Nav.vue'; // ナビゲーションコンポーネント

export default {
    components: {
        Nav, // ナビゲーションコンポーネントを読み込み
    }
}
</script>