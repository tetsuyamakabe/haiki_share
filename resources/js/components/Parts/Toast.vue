<template>
    <!-- フラッシュメッセージの表示 -->
    <div v-if="flashMessage.message" :class="`c-toast c-toast--${flashMessage.type}`">
        {{ flashMessage.message }}
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    computed: {
        ...mapGetters('flash', ['flashMessage']) // flashモジュールのflashMessageゲッターを取得
    }
}
</script>