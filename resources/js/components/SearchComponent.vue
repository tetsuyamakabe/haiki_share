<template>
    <section class="l-article__sidebar">
        <div class="p-sidebar">
            <h2 class="c-title c-title--sub">絞り込み検索</h2>
            <form @submit.prevent="submitForm" class="c-form c-form--search">
                <!-- 都道府県 -->
                <label class="c-label">出品したコンビニがある都道府県</label>
                <select class="c-selectbox" v-model="selectedPrefecture">
                    <option value="">都道府県を選択</option>
                    <option v-for="prefecture in prefectures" :key="prefecture">{{ prefecture }}</option>
                </select>
                <!-- 価格 -->
                <label class="c-label">最低価格</label>
                <span v-if="errors && errors.minprice" class="c-error">{{ errors.minprice[0] }}</span>
                <input class="c-input c-input__search" type="text" name="minprice" maxlength="4" v-model="minPrice" placeholder="半角数字で入力"><span class="c-text">円</span>
                <label class="c-label">最高価格</label>
                <span v-if="errors && errors.maxprice" class="c-error">{{ errors.maxprice[0] }}</span>
                <input class="c-input c-input__search" type="text" name="maxprice" maxlength="4" v-model="maxPrice" placeholder="半角数字で入力"><span class="c-text">円</span>
                <!-- 賞味期限切れかどうか -->
                <label class="c-label">賞味期限切れかどうか</label>
                <div class="c-input__radio">
                    <input type="radio" value="true" v-model="isExpired"><label class="c-text">賞味期限切れ</label></br>
                    <input type="radio" value="false" v-model="isExpired"><label class="c-text">賞味期限内</label>
                </div>

                <label class="c-label">並び替え</label>
                <select class="c-selectbox" v-model="sortOrder">
                    <option value="">選択してください</option>
                    <option value="desc">新しい順</option>
                    <option value="asc">古い順</option>
                </select>
                <!-- 検索ボタン -->
                <button type="submit" class="c-button c-button--submit c-button--primary">商品を検索する</button>
            </form>
        </div>
    </section>
</template>

<script>
export default {
    props: ['errors'],

    data() {
        return {
            prefectures: [], // 都道府県の選択肢
            selectedPrefecture: '', // 都道府県
            minPrice: 0, // 最小価格
            maxPrice: 0, // 最大価格
            isExpired: '', // 賞味期限切れかどうか
            sortOrder: '', // 並び替えの順序を保持する変数
        };
    },

    created() {
        this.getPrefectures(); // インスタンス初期化時に都道府県情報を読み込む
    },

    methods: {
        // 出品しているコンビニがある都道府県の取得
        async getPrefectures() {
            try {
                console.log('都道府県情報を取得します');
                // 出品しているコンビニがある都道府県の取得APIのGET送信
                const response = await axios.get('/api/prefecture');
                console.log('APIからのレスポンス:', response.data);
                this.prefectures = response.data.prefectures;
            } catch (error) {
                console.error('都道府県情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            }
        },

        // 検索フォームの値をサーバー側に送信するメソッド
        submitForm() {
            console.log('検索条件を送信します');
            let params = {}; // paramsオブジェクト
            if (this.selectedPrefecture) { // 検索条件に都道府県がある場合
                params.prefecture = this.selectedPrefecture; // paramsオブジェクトにselectedPrefectureを入れる
            }
            if (this.minPrice) { // 検索条件に最小価格がある場合
                params.minprice = this.minPrice; // paramsオブジェクトにminPriceを入れる
            }
            if (this.maxPrice) { // 検索条件に最大価格がある場合
                params.maxprice = this.maxPrice; // paramsオブジェクトにmaxPriceを入れる
            }
            if (this.isExpired !== null) { // 検索条件に賞味期限がある場合
                params.expiration_date = this.isExpired; // paramsオブジェクトにisExpiredを入れる
            }
            if (this.sortOrder) { // ソート順が選択されている場合
                params.sort = this.sortOrder; // paramsオブジェクトにsortOrderを入れる
            }
            console.log('検索パラメータ:', params);
            // 親コンポーネントに通知
            this.$emit('search', params, this.errors); // パラメータをつけたオブジェクトをemitする。バリデーションメッセージを表示するerrorsを追加。
        },
    }
}
</script>