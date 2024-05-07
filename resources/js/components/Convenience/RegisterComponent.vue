<template>
    <main class="l-main">
        <div class="p-register">
            <div class="p-register__form">
                <h1 class="c-title">ユーザー登録</h1>
                <div class="p-container">
                    <form @submit.prevent="submitForm">

                        <!-- バリデーションエラーメッセージ -->
                        <span v-if="errors && errors.name" class="c-error">{{ errors.name[0] }}</span>
                        <span v-if="errors && errors.branch_name" class="c-error">{{ errors.branch_name[0] }}</span>
                        <span v-if="errors && errors.address" class="c-error">{{ errors.address[0] }}</span>
                        <span v-if="errors && errors.prefecture" class="c-error">{{ errors.prefecture[0] }}</span>
                        <span v-if="errors && errors.city" class="c-error">{{ errors.city[0] }}</span>
                        <span v-if="errors && errors.town" class="c-error">{{ errors.town[0] }}</span>
                        <span v-if="errors && errors.building" class="c-error">{{ errors.building[0] }}</span>
                        <span v-if="errors && errors.password" class="c-error">{{ errors.password[0] }}</span>
                        <span v-if="errors && errors.password_confirmation" class="c-error">{{ errors.password_confirmation[0] }}</span>
                        <span v-if="errors && !agreement" class="c-error">利用規約に同意する必要があります</span>

                        <table>
                            <tr>
                                <th><label for="name" class="c-label">コンビニ名</label></th>
                                <td>
                                    <input v-model="formData.name" id="name" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.name }" autocomplete="name">
                                </td>
                            </tr>
                            <tr>
                                <th><label for="branch_name" class="c-label">支店名</label></th>
                                <td>
                                    <input v-model="formData.branch_name" id="branch_name" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.branch_name }" autocomplete="branch_name">
                                </td>
                            </tr>
                            <tr>
                                <th><label for="address" class="c-label">住所</label></th>
                                <td>
                                    <input v-model="formData.postalcode" id="address" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.address }" autocomplete="address">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: right;">
                                    <button type="button" class="c-button" @click="searchAddress">郵便番号検索</button>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="prefecture" class="c-label">都道府県</label></th>
                                <td>
                                    <input v-model="formData.prefecture" id="prefecture" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.prefecture }" autocomplete="prefecture">
                                </td>
                            </tr>
                            <tr>
                                <th><label for="city" class="c-label">市区町村</label></th>
                                <td>
                                    <input v-model="formData.city" id="city" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.city }" autocomplete="city">
                                </td>
                            </tr>
                            <tr>
                                <th><label for="town" class="c-label">地名・番地</label></th>
                                <td>
                                    <input v-model="formData.town" id="town" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.town }" autocomplete="town">
                                </td>
                            </tr>
                            <tr>
                                <th><label for="building" class="c-label">建物名・部屋番号</label></th>
                                <td>
                                    <input v-model="formData.building" id="building" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.building }" autocomplete="building">
                                </td>
                            </tr>
                            <tr>
                                <th><label for="email" class="c-label">メールアドレス</label></th>
                                <td>
                                    <input v-model="formData.email" id="email" type="email" class="c-input" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">
                                </td>
                            </tr>
                            <tr>
                                <th><label for="password" class="c-label">パスワード</label></th>
                                <td>
                                    <div class="p-input__password">
                                        <input v-model="formData.password" id="password" :type="PasswordType" class="c-input" :class="{ 'is-invalid': errors && errors.password }" placeholder="英数字8文字以上で入力してください">
                                        <span @click="togglePasswordVisibility('password')"><i :class="PasswordIconClass"></i></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="password-confirm" class="c-label">パスワード（再入力）</label></th>
                                <td>
                                    <div class="p-input__password">
                                        <input v-model="formData.password_confirmation" id="password-confirm" :type="PasswordConfirmType" class="c-input" :class="{ 'is-invalid': errors && errors.password_confirmation }" placeholder="英数字8文字以上で入力してください">
                                        <span @click="togglePasswordVisibility('password_confirm')"><i :class="PasswordConfirmIconClass"></i></span>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <!-- 利用規約 -->
                        <div class="p-container__terms">
                            <terms-component></terms-component>
                        </div>

                        <div class="p-container__checkbox">
                            <input type="checkbox" v-model="agreement" id="agreement">
                            <span class="c-text__center" for="agreement">利用規約に同意します</span>
                        </div>

                        <!-- 登録ボタン -->
                        <div class="p-register__button">
                            <button type="submit" class="c-button">登録する</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <a @click="$router.back()">前のページに戻る</a>
    </main>
</template>

<script>
import axios from 'axios';
import TermsComponent from '../TermsComponent.vue'; // 利用規約
const jsonpAdapter = require('axios-jsonp') // 郵便番号API

export default {
    components: {
        'terms-component': TermsComponent // 利用規約コンポーネントを読み込み
    },

    data() {
        return {
            formData: {
                name: '',
                branch_name: '',
                prefecture: '',
                city: '',
                town: '',
                building: '',
                email: '',
                password: '',
                password_confirmation: '',
                role: 'convenience'
            },
            agreement: false, // 利用規約に同意したかどうか
            errors: null,
            PasswordType: 'password', // パスワードの初期設定
            PasswordConfirmType: 'password', // パスワード（再入力）の初期設定
            PasswordIconClass: 'far fa-eye-slash', // 初期アイコン
            PasswordConfirmIconClass: 'far fa-eye-slash', // 初期アイコン
        };
    },

    methods: {
        // 郵便番号検索APIを使って、郵便番号から住所を自動入力するメソッド
        searchAddress() {
            const postalcode = this.formData.postalcode;
            const zipCode = postalcode;
            axios.get(`https://api.zipaddress.net/?zipcode=${zipCode}`, { adapter: jsonpAdapter }).then(rs => {
                const response = rs.data;
                // 都道府県、市区町村、地名・番地をセット
                this.formData.prefecture = response.pref;
                this.formData.city = response.city;
                this.formData.town = response.town;
                this.formData.building = response.building;
                console.log('townは、', this.formData.town);
            }).catch(error => {
                console.error('住所検索エラー:', error);
            });
        },

        // 入力された値をサーバー側に送信するメソッド
        submitForm() {
            this.formData.agreement = this.agreement;
            axios.post('/api/convenience/register', this.formData).then(response => {
                console.log('ユーザー登録します。');
                this.$router.push({ name: 'convenience.mypage' }); // ユーザー登録後、マイページに遷移
            }).catch(error => {
                console.log('errorは、', error);
                console.error('ユーザー登録失敗:', error.response.data);
                this.errors = error.response.data.errors;
            });
        },

        // パスワードの表示・非表示を切り替えるメソッド
        togglePasswordVisibility(type) {
            if (type === 'password') {
                this.PasswordType = this.PasswordType === 'password' ? 'text' : 'password';
                this.PasswordIconClass = this.PasswordIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash';
            } else if (type === 'password_confirm') {
                this.PasswordConfirmType = this.PasswordConfirmType === 'password' ? 'text' : 'password';
                this.PasswordConfirmIconClass = this.PasswordConfirmIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash';
            }
        }
    }
}
</script>