<template>
    <!-- ページネーション -->
    <ul class="pagination">
        <!-- 前に移動する<<ボタン -->
        <li :class="{ 'inactive': currentPage == 1, 'disabled': currentPage == 1 }" @click="changePage(currentPage - 1)">«</li>
        <!-- ページ番号の範囲 -->
        <li v-for="page in frontPageRange" :key="page" @click="changePage(page)" :class="{ 'active': isCurrent(page), 'inactive': !isCurrent(page) }">{{ page }}</li>
        <!-- ドットの表示 -->
        <li v-if="front_dot" class="inactive disabled">...</li>
        <!-- ページ番号の範囲 -->
        <li v-for="page in middlePageRange" :key="page" @click="changePage(page)" :class="{ 'active': isCurrent(page), 'inactive': !isCurrent(page) }">{{ page }}</li>
        <!-- ドットの表示 -->
        <li v-if="end_dot" class="inactive">...</li>
        <!-- 次に移動する>>ボタン -->
        <li :class="{ 'inactive': currentPage >= last_page, 'disabled': currentPage >= last_page }" @click="changePage(currentPage + 1)">»</li>
    </ul>
</template>

<script>
export default {
    props: ['current_page', 'last_page'],
    data() {
        return {
            currentPage: 1, // currentPageをpropsから受け取る
            range: 5, // 表示されるページの範囲
            front_dot: false, // 前のページにドットを表示させるか
            end_dot: false // 後ろのページにドットを表示させるか
        };
    },

    computed: {
        // ページネーションの表示を制御するためのメソッド
        sizeCheck() {
            console.log('sizecheckメソッド');
            console.log('this.rangeは、', this.range);
            if (this.last_page <= this.range + 4) {
                return false;
            }
            return true;
        },

        // 最初のページ番号の範囲
        frontPageRange() {
            if (!this.sizeCheck) {
                this.front_dot = false;
                this.end_dot = false;
                return this.calRange(1, this.last_page);
            }
            return this.calRange(1, 2);
        },

        // 中間のページ番号の範囲
        middlePageRange() {
            if (!this.sizeCheck) return [];
            let start = "";
            let end = "";
            if (this.currentPage <= this.range) {
                start = 3;
                end = this.range + 2;
                this.front_dot = false;
                this.end_dot = true;
            } else if (this.currentPage > this.last_page - this.range) {
                start = this.last_page - this.range - 1;
                end = this.last_page - 2;
                this.front_dot = true;
                this.end_dot = false;
            } else {
                start = this.currentPage - Math.floor(this.range / 2);
                end = this.currentPage + Math.floor(this.range / 2);
                this.front_dot = true;
                this.end_dot = true;
            }
            return this.calRange(start, end);
        },

        // 最後のページ番号の範囲
        lastPageRange() {
            if (!this.sizeCheck) return [];
            return this.calRange(this.last_page - 1, this.last_page);
        },
    },

    methods: {
        // 配列作成メソッド
        calRange(start, end) {
            const range = [];
            for (let i = start; i <= end; i++) {
                range.push(i);
            }
            return range;
        },

        // ページが現在のページかどうかを確認するメソッド
        isCurrent(page) {
            return page === this.currentPage;
        },

        // ページが変更されたときの処理
        changePage(page) {
            console.log('changePageメソッドです。');
            console.log('pageは、', page);
            console.log('this.currentPageは、', this.currentPage);
            if (page > 0 && page <= this.last_page) {
                console.log('this.currentPageは、', this.currentPage);
                this.currentPage = page;
                console.log('pageは、', page);
                this.$emit("onClick", this.currentPage); // 親コンポーネントに正しいページ番号を伝達
            }
        },
    },
};
</script>

<style scoped>
.pagination {
    display: flex;
    list-style-type: none;
}

.pagination li {
    border: 1px solid #ddd;
    padding: 6px 12px;
    text-align: center;
}

.active {
    background-color: #337;
    color:white;
}

.inactive{
    color: #337;
}

.pagination li + li {
    border-left: none;
}
.disabled {
    cursor: not-allowed;
}
</style>
