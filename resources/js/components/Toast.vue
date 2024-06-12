<template>
    <div v-if="flashMessage.message" :class="`c-toast c-toast__${flashMessage.type}`">
        {{ flashMessage.message }}
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    computed: {
        ...mapGetters('flash', ['flashMessage']) // モジュール名を指定してgetterを取得
    }
}
</script>