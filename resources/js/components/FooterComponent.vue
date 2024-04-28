<template>
    <footer id="l-footer">
        <a id="l-footer__pagetop" href="#"><div id="l-footer__pagetop--arrow"></div></a>
        <div id="l-footer__inner">
            <span id="l-footer__text">Copyright</span>
                <router-link class="l-footer__link" :to="{ name: 'home' }">
                    haiki share
                </router-link>
            <span id="l-footer__text">All Rights Reserved.</span>
            <router-link class="l-footer__link" :to="{ name: 'terms' }">
                利用規約
            </router-link>
        </div>
    </footer>
</template>

<script>
    export default {}
</script>