<template>
    <section class="l-sidebar">
        <div class="p-mypage__sidebar">
            <div class="p-search__form u-pd__s">
                <h3 class="c-title c-title__sub u-mt__m u-mb__m">絞り込み検索</h3>
                <div class="p-search__container">
                    <form @submit.prevent="submitForm" class="c-form u-pd__s">

                        <!-- 都道府県 -->
                        <label class="c-label">出品したコンビニがある都道府県</label>
                        <select class="c-selectbox u-mt__s u-mb__s" v-model="selectedPrefecture">
                            <option value="">都道府県を選択</option>
                            <option v-for="prefecture in prefectures" :key="prefecture">{{ prefecture }}</option>
                        </select>

                        <!-- 価格 -->
                        <label class="c-label">最低価格</label>
                        <input class="c-input c-input__search u-mt__s u-mb__s u-pd__s" type="number" name="minprice" v-model="minPrice"><span class="c-text u-ml__s">円</span>
                        <label class="c-label">最高価格</label>
                        <input class="c-input c-input__search u-mt__s u-mb__s u-pd__s" type="number" name="maxprice" v-model="maxPrice"><span class="c-text u-ml__s">円</span>

                        <!-- 賞味期限切れかどうか -->
                        <label class="c-label">賞味期限切れかどうか</label>
                        <div class="c-input__radio u-mt__s u-mb__s">
                            <div class="p-search__expired">
                                <input type="radio" value="true" v-model="isExpired"><label class="c-text u-ml__s">賞味期限切れ</label>
                            </div>
                            <div class="p-search__expired">
                                <input type="radio" value="false" v-model="isExpired"><label class="c-text u-ml__s">賞味期限内</label>
                            </div>
                        </div>

                        <!-- 検索ボタン -->
                        <button type="submit" class="c-button c-button__submit c-button__common u-pd__s u-mt__m u-mb__m">検索する</button>

                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            prefectures: [],
            selectedPrefecture: '', // 都道府県
            minPrice: 0, // 最小価格
            maxPrice: 0, // 最大価格
            isExpired: '' // 賞味期限切れかどうか
        };
    },

    created() {
        this.getPrefectures();
    },

    methods: {
        // 出品しているコンビニがある都道府県の取得
        getPrefectures() {
            console.log('都道府県情報を取得します');
            axios.get('/api/prefecture').then(response => {
                console.log('APIからのレスポンス:', response.data);
                this.prefectures = response.data.prefectures;
            }).catch(error => {
                console.error('都道府県情報取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // 検索フォームの値をサーバー側に送信するメソッド
        submitForm() {
            console.log('検索条件を送信します');
            let params = {};

            if (this.selectedPrefecture) {
                params.prefecture = this.selectedPrefecture;
            }
            if (this.minPrice) {
                params.minprice = this.minPrice;
            }
            if (this.maxPrice) {
                params.maxprice = this.maxPrice;
            }
            if (this.isExpired !== null) {
                params.expiration_date = this.isExpired;
            }
            console.log('検索パラメータ:', params);
            this.$emit('search', params); // パラメータをつけたオブジェクトをemitする
        },
    }
}
</script>