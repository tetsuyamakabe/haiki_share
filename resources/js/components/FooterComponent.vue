<template>
    <footer class="l-footer">
        <a class="l-footer__pagetop" href="#"><span class="l-footer__pagetop--arrow"></span></a>
        <div class="l-footer__inner">
            <span class="l-footer__copyright">Copyright</span>
            <router-link class="c-link" :to="{ name: 'top' }">©︎haiki share</router-link>
            <span class="l-footer__copyright">All Rights Reserved.</span>
            <div class="l-footer__link">
                <router-link class="c-link" :to="{ name: 'terms' }">利用規約</router-link>
                <router-link class="c-link" :to="{ name: 'privacy' }">プライバシーポリシー</router-link>
                <router-link class="c-link" :to="{ name: 'contact' }">お問い合わせ</router-link>
            </div>
        </div>
    </footer>
</template>

<script>
    export default {}
</script>