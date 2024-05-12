<template>
    <main class="l-main">
        <div class="l-main__convenience">
            <section class="p-register">
                <h1 class="c-title u-mb__xl">コンビニプロフィール編集</h1>
                <form @submit.prevent="submitForm" class="c-form">

                    <!-- バリデーションエラーメッセージ -->
                    <span v-if="errors && errors.name" class="c-error">{{ errors.name[0] }}</span>
                    <span v-if="errors && errors.branch_name" class="c-error">{{ errors.branch_name[0] }}</span>
                    <span v-if="errors && errors.address" class="c-error">{{ errors.address[0] }}</span>
                    <span v-if="errors && errors.prefecture" class="c-error">{{ errors.prefecture[0] }}</span>
                    <span v-if="errors && errors.city" class="c-error">{{ errors.city[0] }}</span>
                    <span v-if="errors && errors.town" class="c-error">{{ errors.town[0] }}</span>
                    <span v-if="errors && errors.building" class="c-error">{{ errors.building[0] }}</span>
                    <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
                    <span v-if="errors && errors.password" class="c-error">{{ errors.password[0] }}</span>
                    <span v-if="errors && errors.password_confirmation" class="c-error">{{ errors.password_confirmation[0] }}</span>
                    <span v-if="errors && errors.introduction" class="c-error">{{ errors.introduction[0] }}</span>
                    <span v-if="errors && errors.icon" class="c-error">{{ errors.icon[0] }}</span>

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
                            <th><label for="address" class="c-label">郵便番号</label></th>
                            <td>
                                <input v-model="formData.postalcode" id="address" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.address }" autocomplete="address" placeholder="郵便番号で住所を簡単入力できます">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right;">
                                <button type="button" class="c-button c-button__convenience u-mr__s" @click="searchAddress">郵便番号検索</button>
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
                                <div class="p-textarea__form">
                                    <textarea v-model.trim="formData.introduction" maxlength="50" id="introduction" type="text" class="c-textarea" autocomplete="introduction" @keyup="countCharacters" :class="{ 'is-invalid': errors && errors.introduction }" placeholder="50文字以内で入力してください"></textarea>
                                    <span class="c-textarea__count">{{ formData.introduction.length }} / 50文字</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="icon" class="c-label">顔写真</label></th>
                            <td>
                                <div class="p-icon" @drop="handleDrop" :class="{ 'is-invalid': errors && errors.icon }">
                                    <input type="file" id="icon" @change="handleFileChange" class="c-input__hidden">
                                    <img v-if="!iconPreview && formData.icon" :src="'/storage/icons/' + formData.icon" alt="アップロード顔写真" class="c-icon">
                                    <img v-else-if="iconPreview" :src="iconPreview" alt="アップロード顔写真" class="c-icon">
                                    <img v-else src="/default.png" alt="デフォルト顔写真" class="c-icon">
                                    <span v-if="!formData.icon">ドラッグ＆ドロップ</span>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <!-- 更新ボタン -->
                    <button type="submit" class="c-button c-button__submit c-button__convenience">プロフィール更新する</button>
                </form>
            </section>
        </div>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
import axios from 'axios';
const jsonpAdapter = require('axios-jsonp')

export default {
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
                introduction: '',
                icon: '',
            },
            textareaCount: 0, // 自己紹介文の文字数カウント初期値
            iconPreview: '',
            errors: null,
            PasswordType: 'password', // パスワードの初期設定
            PasswordConfirmType: 'password', // パスワード（再入力）の初期設定
            PasswordIconClass: 'far fa-eye-slash', // 初期アイコン
            PasswordConfirmIconClass: 'far fa-eye-slash', // 初期アイコン
        };
    },

    created() {
        this.getProfile(); // プロフィール情報を取得
    },

    methods: {
        // 編集前のプロフィール情報をサーバーから取得
        getProfile() {
            console.log('プロフィールを取得します');
            axios.get('/api/convenience/mypage/profile').then(response => {
                this.user = response.data.user;
                this.convenience = response.data.convenience;
                this.address = response.data.address;
                console.log('APIからのレスポンス:', response.data);
                // 取得したプロフィール情報をformDataに入れる
                this.formData.name = this.user.name || '';
                this.formData.branch_name = this.convenience.branch_name || '',
                this.formData.prefecture = this.address.prefecture || '',
                this.formData.city = this.address.city || '',
                this.formData.town = this.address.town || '',
                this.formData.building = this.address.building || '',
                this.formData.email = this.user.email || '';
                this.formData.password = ''; // 編集前のパスワードは非表示（入力フォームを空）にする
                this.formData.password_confirmation = ''; // 編集前のパスワード（再入力）は非表示（入力フォームを空）にする
                this.formData.introduction = this.user.introduction || '';
                this.formData.icon = this.user.icon || '';
            }).catch(error => {
                console.error('プロフィール取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

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
            // リクエストヘッダー定義
            const config = {
                headers: {
                    'content-type': 'multipart/form-data',
                }
            };

            // フォームデータを作成
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', this.formData.name);
            formData.append('branch_name', this.formData.branch_name);
            formData.append('prefecture', this.formData.prefecture);
            formData.append('city', this.formData.city);
            formData.append('town', this.formData.town);
            formData.append('building', this.formData.building);
            formData.append('email', this.formData.email);
            formData.append('password', this.formData.password);
            formData.append('password_confirmation', this.formData.password_confirmation);
            formData.append('introduction', this.formData.introduction);
            
            // icon フィールドが空でない場合のみ、フォームデータに追加
            if (this.formData.icon !== '') {
                formData.append('icon', this.formData.icon);
            }

            axios.post('/api/convenience/mypage/profile', formData, config).then(response => {
                this.message = response.data.message;
                console.log('this.messageは、', this.message);
                console.log('プロフィールを更新します。');
                this.$router.push({ name: 'convenience.mypage' });
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

        // 自己紹介文の文字数をカウントするメソッド
        countCharacters() {
            this.countCharactersLength = this.formData.introduction.length;
            if (this.countCharactersLength > 50) {
                this.formData.introduction = this.formData.introduction.slice(0, 50);
                this.countCharactersLength = 50;
            }
        },

        // ドラッグ＆ドロップエリアに画像がドロップされたときの処理
        handleDrop(event) {
            event.preventDefault();
            event.dataTransfer.files[0];
        },

        // ファイルが選択されたときの処理
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                // プレビューを表示する
                this.previewImage(file);
                // formData.iconにファイルオブジェクトを設定
                this.formData.icon = file;
            } else {
                this.formData.icon = null;
            }
        },

        // 画像のプレビューを表示するメソッド
        previewImage(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                // プレビュー画像のURLを生成し、formDataに設定
                this.iconPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}
</script>