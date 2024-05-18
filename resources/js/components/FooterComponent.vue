<template>
    <footer id="l-footer">
        <a id="l-footer__pagetop" href="#"><div id="l-footer__pagetop--arrow"></div></a>
        <div id="l-footer__inner" class="u-pd__xxl">
            <span id="l-footer__text">Copyright</span>
                <router-link class="c-link" :to="{ name: 'home' }">
                    haiki share
                </router-link>
            <span id="l-footer__text">All Rights Reserved.</span>
            <div class="l-footer__link">
                <router-link class="c-link" :to="{ name: 'terms' }">利用規約</router-link>
                <router-link class="c-link" :to="{ name: 'privacy' }">プライバシーポリシー</router-link>
            </div>
        </div>
    </footer>
</template>

<script>
    export default {}
</script>