<template>
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

            <table>
                <tr>
                    <th><label for="name" class="c-label">コンビニ名</label></th>
                    <td>
                        <input v-model="formData.name" id="name" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.name }">
                    </td>
                </tr>
                <tr>
                    <th><label for="branch_name" class="c-label">支店名</label></th>
                    <td>
                        <input v-model="formData.branch_name" id="branch_name" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.branch_name }">
                    </td>
                </tr>
                <tr>
                    <th><label for="address" class="c-label">住所</label></th>
                    <td>
                        <input v-model="formData.postalcode" id="address" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.address }">
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
                        <input v-model="formData.prefecture" id="prefecture" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.prefecture }">
                    </td>
                </tr>
                <tr>
                    <th><label for="city" class="c-label">市区町村</label></th>
                    <td>
                        <input v-model="formData.city" id="city" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.city }">
                    </td>
                </tr>
                <tr>
                    <th><label for="town" class="c-label">地名・番地</label></th>
                    <td>
                        <input v-model="formData.town" id="town" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.town }">
                    </td>
                </tr>
                <tr>
                    <th><label for="building" class="c-label">建物名・部屋番号</label></th>
                    <td>
                        <input v-model="formData.building" id="building" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.building }">
                    </td>
                </tr>
                <tr>
                    <th><label for="email" class="c-label">メールアドレス</label></th>
                    <td>
                        <input v-model="formData.email" id="email" type="email" class="c-input" :class="{ 'is-invalid': errors && errors.email }">
                    </td>
                </tr>
                <tr>
                    <th><label for="password" class="c-label">パスワード</label></th>
                    <td>
                        <div class="p-input__password">
                            <input v-model="formData.password" id="password" :type="PasswordType" class="c-input" :class="{ 'is-invalid': errors && errors.password }" autocomplete="new-password" placeholder="英数字8文字以上で入力してください">
                            <span @click="togglePasswordVisibility('password')"><i :class="PasswordIconClass"></i></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th><label for="password-confirm" class="c-label">パスワード（再入力）</label></th>
                    <td>
                        <div class="p-input__password">
                            <input v-model="formData.password_confirmation" id="password-confirm" :type="PasswordConfirmType" class="c-input" :class="{ 'is-invalid': errors && errors.password_confirmation }" autocomplete="new-password" placeholder="英数字8文字以上で入力してください">
                            <span @click="togglePasswordVisibility('password_confirm')"><i :class="PasswordConfirmIconClass"></i></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th><label for="introduction" class="c-label">自己紹介</label></th>
                    <td>
                        <textarea v-model="formData.introduction" id="introduction" type="text" class="c-input" autocomplete="introduction"></textarea>
                    </td>
                </tr>

                <tr>
                    <th><label for="profile_image" class="c-label">顔写真</label></th>
                    <td>
                        <input type="file" id="profile_image" @change="handleFileChange">
                    </td>
                </tr>
            </table>

            <!-- 更新ボタン -->
            <div class="p-profile__button">
                <button type="submit" class="c-button">更新する</button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
const jsonpAdapter = require('axios-jsonp')

export default {
    props: ['user', 'convenience', 'address'],
    data() {
        return {
            formData: {
                name: this.user.name || '',
                branch_name: this.convenience.branch_name || '',
                prefecture: this.address.prefecture || '',
                city: this.address.city || '',
                town: this.address.town || '',
                building: this.address.building || '',
                email: this.user.email || '',
                password: '',
                password_confirmation: '',
                introduction: this.user.introduction || '',
                profile_image: null,
            },
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
            axios.put('/convenience/mypage/profile', this.formData).then(response => {
                const userId = response.data.user_id; // レスポンスからユーザーIDを取得
                console.log('userIdは、', userId);
                window.location.href = '/convenience/mypage/' + userId; // ユーザーIDを含んだリダイレクト先のURLに遷移
            }).catch(error => {
                console.error('プロフィール編集失敗:', error.response.data);
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
        },

        // ファイルが選択されたときの処理
        handleFileChange(event) {
            this.formData.profile_image = event.target.files[0];
        }
    }
}
</script>