<template>
    <div>
        <Header />
        <main>
            <div class="container">
                <RouterView />
            </div>
            <a @click="$router.back()" class="c-link c-link--back">前のページに戻る</a>
        </main>
        <Footer />
    </div>
</template>

<script>
import Header from './components/Parts/Header.vue' // ヘッダーコンポーネント
import Footer from './components/Parts/Footer.vue' // フッターコンポーネント

export default {
    components: {
        Header, // ヘッダーコンポーネントを読み込み
        Footer // フッターコンポーネントを読み込み
    }
}
</script>