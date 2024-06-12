<template>
    <div class="p-pagination">
        <!-- ページネーション -->
        <ul class="c-pagination">
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
            <!-- ページ番号の範囲 -->
            <li v-for="page in endPageRange" :key="page" @click="changePage(page)" :class="{ 'active': isCurrent(page), 'inactive': !isCurrent(page) }">{{ page }}</li>
            <!-- 次に移動する>>ボタン -->
            <li :class="{ 'inactive': currentPage >= last_page, 'disabled': currentPage >= last_page }" @click="changePage(currentPage + 1)">»</li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ['current_page', 'last_page'], // 親コンポーネントからcurrent_pageとlast_pageを受け取る
    data() {
        return {
            currentPage: this.current_page, // currentPageをpropsから受け取る
            range: 5, // 表示されるページの範囲
            start: "", // 開始ページ番号
            end: "", // 終了ページ番号
            front_dot: false, // 先頭のページにドットを表示させるか
            end_dot: false // 末尾のページにドットを表示させるか
        };
    },

    computed: {
        // ページネーションの表示を制御するためのメソッド
        sizeCheck() {
            console.log('sizecheckメソッド');
            console.log('this.rangeは、', this.range);
            if (this.last_page <= this.range + 4) { // 表示するページ数が少ない（最後のページ番号が表示されるページの範囲より4以下）の場合
                return false; // falseを返す
            }
            return true; // そうでなければtrueを返す
        },

        // 最初のページ番号の範囲
        frontPageRange() {
            if (!this.sizeCheck) { // 表示するページ数が少ない場合
                this.front_dot = false; // 先頭のドットは表示しない
                this.end_dot = false; // 末尾のドットは表示しない
                return this.calRange(1, this.last_page); // 1ページ目から最後のページ数を表示
            }
            return this.calRange(1, 2); // 最初のページは2ページ目まで制限
        },

        // 中間のページ番号の範囲
        middlePageRange() {
            if (!this.sizeCheck) return []; // 表示するページ数が少ない場合は空の配列を返す
            if (this.currentPage <= this.range) { // 現在ページが表示されるページの範囲より少ない場合
                start = 3; // 開始ページ番号を3ページ目から表示
                end = this.range + 2; // 終了ページ番号の2ページ分を表示
                this.front_dot = false; // 先頭のドットは表示しない
                this.end_dot = true; // 末尾のドットは表示する
            } else if (this.currentPage > this.last_page - this.range) { // 現在ページが最後のページからページ範囲を引いた数より大きい場合
                start = this.last_page - this.range - 1; // 開始ページ番号を最後のページ数からページ範囲を引いた値に1を引いた数にする
                end = this.last_page - 2; // 終了ページ番号を最後のページ数から2を引いた数にする
                this.front_dot = true; // 先頭のドットは表示する
                this.end_dot = false; // 末尾のドットは表示しない
            } else {
                start = this.currentPage - Math.floor(this.range / 2); // 開始ページ番号を現在ページからページ範囲の半分を引いた数にする
                end = this.currentPage + Math.floor(this.range / 2); // 終了ページ番号を現在ページからページ範囲の半分を引いた数にする
                this.front_dot = true; // 先頭のドットは表示する
                this.end_dot = true; // 末尾のドットは表示する
            }
            return this.calRange(start, end); // 計算された開始ページと終了ページをcalRangeメソッドに渡す
        },

        // 最後のページ番号の範囲
        endPageRange() {
            if (!this.sizeCheck) return []; // 表示するページ数が少ない場合は空の配列を返す
            return this.calRange(this.last_page - 1, this.last_page); // 最後のページ番号の範囲を計算
        },
    },

    watch: {
        // current_pageプロパティの監視
        current_page(newValue) {
            this.currentPage = newValue; // current_pageプロパティの値が変更されると、newValueとしてthis.currentPageに渡される
            console.log('watchのthis.currentPageは、', this.currentPage);
        },
    },

    methods: {
        // 配列作成メソッド
        calRange(start, end) {
            const range = []; // ページ範囲
            for (let i = start; i <= end; i++) { // 開始ページ番号から終了ページ番号を配列に追加を繰り返す
                range.push(i); // 配列に追加
            }
            return range; // 配列を返す
        },

        // ページが現在のページかどうかを確認するメソッド
        isCurrent(page) {
            return page === this.currentPage; // ページ番号が現在ページと同じ場合trueを返す
        },

        // ページが変更されたときの処理
        changePage(page) {
            console.log('changePageメソッドです。');
            console.log('pageは、', page);
            console.log('this.currentPageは、', this.currentPage);
            if (page > 0 && page <= this.last_page) { // ページ番号が0以上、最後のページ番号以下の場合
                console.log('this.currentPageは、', this.currentPage);
                this.currentPage = page; // this.currentPageを更新
                console.log('pageは、', page);
                this.$emit("onClick", this.currentPage); // 親コンポーネントに正しいページ番号を伝達
            }
        },
    },
};
</script>