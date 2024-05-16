<template>
    <div>
        <HeaderComponent />
        <main>
            <div class="container">
                <RouterView />
            </div>
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