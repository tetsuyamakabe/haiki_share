<template>
    <header class="l-header u-pdr__xl u-pdl__xl">
        <div class="l-header__inner">
            <router-link class="l-header__title" :to="{ name: 'home' }">
                haiki share
            </router-link>
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