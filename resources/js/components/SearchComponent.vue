<template>
    <section class="l-side">
        <div class="p-search">
            <div class="p-search__form">
                <h1 class="c-title">商品検索</h1>
                <div class="p-sub__container">
                    <form @submit.prevent="submitForm">
                        <table>
                            <tr>
                                <th><label>都道府県</label></th>
                                <td>
                                    <select v-model="selectedPrefecture">
                                        <option value="">都道府県を選択してください</option>
                                        <option v-for="prefecture in prefectures" :key="prefecture">{{ prefecture }}</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th><label>価格</label></th>
                                <td>
                                    <input type="text" name="minprice" maxlength="9" v-model="minPrice">円〜
                                    <input type="text" name="maxprice" maxlength="9" v-model="maxPrice">円
                                </td>
                            </tr>
                
                            <tr>
                                <th><label>賞味期限切れかどうか</label></th>
                                <td>
                                    <div>
                                        <input type="radio" value="true" v-model="isExpired"><label>賞味期限切れ</label>
                                        <input type="radio" value="false" v-model="isExpired"><label>賞味期限内</label>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <!-- 検索ボタン -->
                        <div class="p-search__button">
                            <button type="submit" class="c-button">検索</button>
                        </div>
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
            minPrice: '', // 最小価格
            maxPrice: '', // 最大価格
            isExpired: false // 賞味期限切れかどうか
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
            console.log('検索結果を表示します');
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