<template>
    <section class="l-side">
        <div class="p-search">
            <div class="p-search__form">
                <h1 class="c-title">商品検索</h1>
                <div class="p-sub__container">
                    <form @submit.prevent="searchProducts">
                        <table>
                            <tr>
                                <th><label>都道府県</label></th>
                                <td>
                                    <select>
                                        <option value="">都道府県を選択してください</option>
                                        <option v-for="prefecture in prefectures" :key="prefecture">{{ prefecture }}</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th><label>価格</label></th>
                                <td>
                                    <input type="text" name="minprice" maxlength="9" value="">円〜
                                    <input type="text" name="maxprice" maxlength="9" value="">円
                                </td>
                            </tr>
                
                            <tr>
                                <th><label>賞味期限切れかどうか</label></th>
                                <td>
                                    <div>
                                        <input type="radio" value="true"><label>賞味期限切れ</label>
                                        <input type="radio" value="false"><label>賞味期限内</label>
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
            products: [],
            prefectures: [],
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
    }
}
</script>