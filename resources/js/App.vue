<template>
    <div>
        <HeaderComponent />
        <main>
            <div class="container">
                <RouterView />
            </div>
            <a @click="$router.back()" class="c-link c-link--back">前のページに戻る</a>
        </main>
        <FooterComponent />
    </div>
</template>

<script>
import HeaderComponent from './components/HeaderComponent.vue'
import FooterComponent from './components/FooterComponent.vue'

export default {
    components: {
        HeaderComponent,
        FooterComponent
    }
}
</script>