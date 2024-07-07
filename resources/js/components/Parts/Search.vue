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

                <!-- 最低価格 -->
                <label class="c-label">最低価格</label>
                <span v-if="errors && errors.minPrice" class="c-error">{{ errors.minPrice[0] }}</span>
                <span v-if="$v.minPrice.$error && !$v.minPrice.numeric && $v.minPrice.$dirty" class="c-error">最低価格は、半角数字で入力してください。</span>
                <span v-if="$v.minPrice.$error && !$v.minPrice.minLength && $v.minPrice.$dirty" class="c-error">最低価格は、0以上で入力してください。</span>
                <input v-model="minPrice" id="minPrice" type="text" class="c-input c-input__search" name="minPrice" maxlength="4" placeholder="半角数字で入力" @blur="$v.minPrice.$touch()" :class="{'is-invalid': $v.minPrice.$error && $v.minPrice.$dirty, 'is-valid': !$v.minPrice.$error && $v.minPrice.$dirty}" autocomplete="minPrice">
                <span class="c-text u-ml__s">円</span>

                <!-- 最高価格 -->
                <label class="c-label">最高価格</label>
                <span v-if="errors && errors.maxPrice" class="c-error">{{ errors.maxPrice[0] }}</span>
                <span v-if="$v.maxPrice.$error && !$v.maxPrice.numeric && $v.maxPrice.$dirty" class="c-error">最高価格は、半角数字で入力してください。</span>
                <span v-if="$v.maxPrice.$error && !$v.maxPrice.minLength && $v.maxPrice.$dirty" class="c-error">最高価格は、0以上で入力してください。</span>
                <input v-model="maxPrice" id="maxPrice" type="text" class="c-input c-input__search" name="maxPrice" maxlength="4" placeholder="半角数字で入力" @blur="$v.maxPrice.$touch()" :class="{'is-invalid': $v.maxPrice.$error && $v.maxPrice.$dirty, 'is-valid': !$v.maxPrice.$error && $v.maxPrice.$dirty}" autocomplete="maxPrice">
                <span class="c-text u-ml__s">円</span>

                <!-- 賞味期限切れかどうか -->
                <label class="c-label">賞味期限切れかどうか</label>
                <div class="c-input__radio">
                    <input type="radio" value="true" v-model="isExpired"><label class="c-text">賞味期限切れ</label></br>
                    <input type="radio" value="false" v-model="isExpired"><label class="c-text">賞味期限内</label>
                </div>

                <!-- カテゴリー -->
                <label class="c-label">商品カテゴリで絞り込み</label>
                <select class="c-selectbox" v-model="selectedCategory">
                    <option value="">商品カテゴリを選択</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                </select>

                <!-- 並び替え -->
                <label class="c-label">並び替え（出品した順）</label>
                <select class="c-selectbox" v-model="sortOrder">
                    <option value="">選択してください</option>
                    <option value="desc">新しい順</option>
                    <option value="asc">古い順</option>
                </select>

                <label class="c-label">並び替え（賞味期限日付順）</label>
                <select class="c-selectbox" v-model="sortExpiredOrder">
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
import { numeric, minLength } from 'vuelidate/lib/validators'; // Vuelidateからバリデータをインポート

export default {
    props: ['errors'],  // 親コンポーネントからerrorsを受け取る

    data() {
        return {
            prefectures: [], // 都道府県の選択肢
            selectedPrefecture: '', // 都道府県
            minPrice: 0, // 最小価格
            maxPrice: 0, // 最大価格
            isExpired: '', // 賞味期限切れかどうか
            categories: [], // 商品カテゴリの選択肢
            selectedCategory: '', // 商品カテゴリ
            sortOrder: '', // 並び替え
            sortExpiredOrder: '',
        };
    },

    validations: { // フロント側のバリデーション
        minPrice: {
            numeric,
            minLength: minLength(0),
        },
        maxPrice: {
            numeric,
            minLength: minLength(0),
        },
    },


    created() {
        this.getPrefectures(); // インスタンス初期化時に都道府県情報を読み込む
        this.getCategories(); // インスタンス初期化時に商品カテゴリ情報を読み込む
    },

    methods: {
        // 出品しているコンビニがある都道府県の取得
        async getPrefectures() {
            try {
                // 出品しているコンビニがある都道府県の取得APIのGET送信
                const response = await axios.get('/api/prefecture');
                this.prefectures = response.data.prefectures;
            } catch (error) {
                this.errors = error.response.data;
            }
        },

        // 商品カテゴリ情報の取得
        async getCategories() {
            try {
                // カテゴリーの取得APIのGET送信
                const response = await axios.get('/api/categories');
                this.categories = response.data.categories;
            } catch (error) {
                this.errors = error.response.data;
            }
        },

        // 検索フォームの値をサーバー側に送信するメソッド
        submitForm() {
            let params = {}; // paramsオブジェクト
            if (this.selectedPrefecture) { // 検索条件に都道府県がある場合
                params.prefecture = this.selectedPrefecture; // paramsオブジェクトにselectedPrefectureを入れる
            }
            if (this.minPrice) { // 検索条件に最小価格がある場合
                params.minPrice = this.minPrice; // paramsオブジェクトにminPriceを入れる
            }
            if (this.maxPrice) { // 検索条件に最大価格がある場合
                params.maxPrice = this.maxPrice; // paramsオブジェクトにmaxPriceを入れる
            }
            if (this.isExpired !== null) { // 検索条件に賞味期限がある場合
                params.expiration_date = this.isExpired; // paramsオブジェクトにisExpiredを入れる
            }
            if (this.selectedCategory) { // 検索条件にカテゴリーがある場合
                params.category_id = this.selectedCategory; // paramsオブジェクトにselectedCategoryを入れる
            }
            if (this.sortOrder) { // ソート順（出品した順）が選択されている場合
                params.sort = this.sortOrder; // paramsオブジェクトにsortOrderを入れる
            }
            if (this.sortExpiredOrder) { // ソート順（賞味期限順）が選択されている場合
                params.sortExpired = this.sortExpiredOrder; // paramsオブジェクトにsortExpiredOrderを入れる
            }
            // 親コンポーネントに通知
            this.$emit('search', params, this.errors); // パラメータをつけたオブジェクトをemitする。バリデーションメッセージを表示するerrorsを追加。
        },
    }
}
</script>