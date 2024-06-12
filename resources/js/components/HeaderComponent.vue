<template>
    <header class="l-header">
        <div class="l-header__inner">
            <router-link class="l-header__logo" :to="{ name: 'top' }">haiki share</router-link>
            <nav-component />
        </div>
    </header>
</template>

<script>
import NavComponent from './NavComponent.vue';

export default {
    components: {
        NavComponent
    }
}
</script>