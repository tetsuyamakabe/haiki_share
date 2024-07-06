<template>
    <main class="l-main">
        <div class="l-main__header">
            <h1 class="c-title">コンビニユーザー登録</h1>
        </div>
        <section class="l-main__wrapper">
            <form @submit.prevent="submitForm" class="c-form">
                <!-- コンビニ名 -->
                <label for="convenience_name" class="c-label">コンビニ名<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.convenience_name" class="c-error">{{ errors.convenience_name[0] }}</span>
                <span v-if="$v.formData.convenience_name.$error && $v.formData.convenience_name.$dirty" class="c-error">コンビニ名が入力されていません。</span>
                <span v-if="$v.formData.convenience_name.$error && !$v.formData.convenience_name.maxLength && $v.formData.convenience_name.$dirty" class="c-error">コンビニ名は、255文字以内で入力してください。</span>
                <input v-model="formData.convenience_name" id="convenience_name" type="text" class="c-input" @blur="$v.formData.convenience_name.$touch()" :class="{'is-invalid': $v.formData.convenience_name.$error && $v.formData.convenience_name.$dirty, 'is-valid': !$v.formData.convenience_name.$error && $v.formData.convenience_name.$dirty}" autocomplete="convenience_name">

                <!-- 支店名 -->
                <label for="branch_name" class="c-label">支店名<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.branch_name" class="c-error">{{ errors.branch_name[0] }}</span>
                <span v-if="$v.formData.branch_name.$error && $v.formData.branch_name.$dirty" class="c-error">支店名が入力されていません。</span>
                <span v-if="$v.formData.branch_name.$error && !$v.formData.branch_name.maxLength && $v.formData.branch_name.$dirty" class="c-error">支店名は、255文字以内で入力してください。</span>
                <input v-model="formData.branch_name" id="branch_name" type="text" class="c-input" @blur="$v.formData.branch_name.$touch()" :class="{'is-invalid': $v.formData.branch_name.$error && $v.formData.branch_name.$dirty, 'is-valid': !$v.formData.branch_name.$error && $v.formData.branch_name.$dirty}" autocomplete="branch_name">

                <!-- 郵便番号 -->
                <label for="zip" class="c-label">郵便番号</label>
                <span class="c-text c-text--note u-fz-10@sm">※数字のみを入力し、ハイフンを含む場合は3桁と4桁の間に挿入してください。ハイフンを含まない場合は数字を7桁入力してください。</span>
                <span v-if="errors && errors.zip" class="c-error">{{ errors.zip[0] }}</span>
                <input v-model="formData.postalcode" id="zip" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.zip }" autocomplete="zip" placeholder="郵便番号で住所を簡単入力できます">
                <div class="c-button--zip">
                    <button type="button" class="c-button c-button--primary" @click="searchAddress">郵便番号検索</button>
                </div>

                <!-- 都道府県 -->
                <label for="prefecture" class="c-label">都道府県<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.prefecture" class="c-error">{{ errors.prefecture[0] }}</span>
                <span v-if="$v.formData.prefecture.$error && $v.formData.prefecture.$dirty" class="c-error">都道府県が入力されていません。</span>
                <span v-if="$v.formData.prefecture.$error && !$v.formData.prefecture.maxLength && $v.formData.prefecture.$dirty" class="c-error">都道府県は、255文字以内で入力してください。</span>
                <input v-model="formData.prefecture" id="prefecture" type="text" class="c-input" @blur="$v.formData.prefecture.$touch()" :class="{'is-invalid': $v.formData.prefecture.$error && $v.formData.prefecture.$dirty, 'is-valid': !$v.formData.prefecture.$error && $v.formData.prefecture.$dirty}" autocomplete="prefecture">

                <!-- 市区町村 -->
                <label for="city" class="c-label">市区町村<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.city" class="c-error">{{ errors.city[0] }}</span>
                <span v-if="$v.formData.city.$error && $v.formData.city.$dirty" class="c-error">市区町村が入力されていません。</span>
                <span v-if="$v.formData.city.$error && !$v.formData.city.maxLength && $v.formData.city.$dirty" class="c-error">市区町村は、255文字以内で入力してください。</span>
                <input v-model="formData.city" id="city" type="text" class="c-input" @blur="$v.formData.city.$touch()" :class="{'is-invalid': $v.formData.city.$error && $v.formData.city.$dirty, 'is-valid': !$v.formData.city.$error && $v.formData.city.$dirty}" autocomplete="city">

                <!-- 地名・番地 -->
                <label for="town" class="c-label">地名・番地<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.town" class="c-error">{{ errors.town[0] }}</span>
                <span v-if="$v.formData.town.$error && $v.formData.town.$dirty" class="c-error">地名・番地が入力されていません。</span>
                <span v-if="$v.formData.town.$error && !$v.formData.town.maxLength && $v.formData.town.$dirty" class="c-error">地名・番地は、255文字以内で入力してください。</span>
                <input v-model="formData.town" id="town" type="text" class="c-input" @blur="$v.formData.town.$touch()" :class="{'is-invalid': $v.formData.town.$error && $v.formData.town.$dirty, 'is-valid': !$v.formData.town.$error && $v.formData.town.$dirty}" autocomplete="town">

                <!-- 建物名・部屋番号 -->
                <label for="building" class="c-label">建物名・部屋番号</label>
                <span v-if="errors && errors.building" class="c-error">{{ errors.building[0] }}</span>
                <span v-if="$v.formData.building.$error && !$v.formData.building.maxLength && $v.formData.building.$dirty" class="c-error">建物名・部屋番号は、255文字以内で入力してください。</span>
                <input v-model="formData.building" id="building" type="text" class="c-input" @blur="$v.formData.building.$touch()" :class="{'is-invalid': $v.formData.building.$error && $v.formData.building.$dirty, 'is-valid': !$v.formData.building.$error && $v.formData.building.$dirty}" autocomplete="building">
                
                <!-- メールアドレス -->
                <label for="email" class="c-label">メールアドレス<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
                <span v-if="$v.formData.email.$error && $v.formData.email.$dirty" class="c-error">メールアドレスが入力されていません。</span>
                <span v-if="$v.formData.email.$error && !$v.formData.email.maxLength && $v.formData.email.$dirty" class="c-error">メールアドレスは、255文字以内で入力してください。</span>
                <span v-if="$v.formData.email.$error && !$v.formData.email.email && $v.formData.email.$dirty" class="c-error">有効なメールアドレスを入力してください。</span>
                <input v-model="formData.email" id="email" type="text" class="c-input" @blur="$v.formData.email.$touch()" :class="{'is-invalid': $v.formData.email.$error && $v.formData.email.$dirty, 'is-valid': !$v.formData.email.$error && $v.formData.email.$dirty}" autocomplete="email">

                <!-- パスワード -->
                <label for="password" class="c-label">パスワード<span class="c-badge">必須</span></label>
                <span class="c-text c-text--note u-fz-10@sm">※パスワードとパスワード（再入力）は、半角数字・英字大文字・小文字、記号（!@#$%^&*）を使って8文字以上で入力してください</span>
                <span v-if="errors && errors.password" class="c-error">{{ errors.password[0] }}</span>
                <span v-if="$v.formData.password.$error && $v.formData.password.$dirty" class="c-error">パスワードが入力されていません。</span>
                <span v-if="$v.formData.password.$error && !$v.formData.password.minLength && $v.formData.password.$dirty" class="c-error">パスワードは、8文字以上で入力してください。</span>
                <span v-if="$v.formData.password.$error && !$v.formData.password.validPasswordFormat && $v.formData.password.$dirty" class="c-error">パスワードは半角数字・英字大文字・小文字、記号（!@#$%^&*）で入力してください。</span>
                <div class="c-password">
                    <input v-model="formData.password" id="password" :type="PasswordType" class="c-input" @blur="$v.formData.password.$touch()" :class="{'is-invalid': $v.formData.password.$error && $v.formData.password.$dirty, 'is-valid': !$v.formData.password.$error && $v.formData.password.$dirty}" placeholder="8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('password')" class="c-password__icon">
                        <i :class="PasswordIconClass"></i>
                    </span>
                </div>

                <!-- パスワード（再入力） -->
                <label for="password-confirm" class="c-label">パスワード（再入力）<span class="c-badge">必須</span></label>
                <span v-if="errors && errors.password_confirmation" class="c-error">{{ errors.password_confirmation[0] }}</span>
                <span v-if="$v.formData.password_confirmation.$error && $v.formData.password_confirmation.$dirty" class="c-error">パスワード（再入力）が入力されていません。</span>
                <span v-if="$v.formData.password_confirmation.$error && !$v.formData.password_confirmation.minLength && $v.formData.password_confirmation.$dirty" class="c-error">パスワード（再入力）は、8文字以上で入力してください。</span>
                <span v-if="$v.formData.password_confirmation.$error && !$v.formData.password_confirmation.validPasswordFormat && $v.formData.password_confirmation.$dirty" class="c-error">パスワード（再入力）は半角数字・英字大文字・小文字、記号（!@#$%^&*）で入力してください。</span>
                <div class="c-password">
                    <input v-model="formData.password_confirmation" id="password-confirm" :type="PasswordConfirmType" class="c-input" @blur="$v.formData.password_confirmation.$touch()" :class="{'is-invalid': $v.formData.password_confirmation.$error && $v.formData.password_confirmation.$dirty, 'is-valid': !$v.formData.password_confirmation.$error && $v.formData.password_confirmation.$dirty}" placeholder="8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('password_confirm')" class="c-password__icon">
                        <i :class="PasswordConfirmIconClass"></i>
                    </span>
                </div>

                <!-- 利用規約 -->
                <div class="p-register">
                    <terms-component></terms-component>
                </div>
                <span v-if="errors && !agreement" class="c-error">利用規約に同意する必要があります。</span>
                <!-- 利用規約チェックボックス -->
                <div class="c-form__group">
                    <input class="c-checkbox" type="checkbox" v-model="agreement" id="agreement">
                    <span class="c-text" for="agreement">利用規約に同意します</span>
                </div>
                <!-- 登録ボタン -->
                <button type="submit" class="c-button c-button--submit c-button--main">ユーザー登録する</button>
            </form>
        </section>
    </main>
</template>

<script>
import TermsComponent from '../Common/TermsComponent.vue'; // 利用規約コンポーネント
const jsonpAdapter = require('axios-jsonp'); // 郵便番号API
import { required, maxLength, email, minLength, helpers } from 'vuelidate/lib/validators'; // Vuelidateからバリデータをインポート
const validPasswordFormat = helpers.regex('validPasswordFormat', /^[a-zA-Z0-9!@#$%^&*]+$/); // パスワードとパスワード（再入力）の正規表現バリデーション

export default {
    components: {
        'terms-component': TermsComponent // 利用規約コンポーネントを読み込み
    },

    data() {
        return {
            formData: {
                convenience_name: '', // コンビニ名
                branch_name: '', // 支店名
                prefecture: '', // 都道府県
                city: '', // 市区町村
                town: '', // 地名・番地
                building: '', // 建物名・部屋番号
                email: '', // メールアドレス
                password: '', // パスワード
                password_confirmation: '', // パスワード（再入力）
                role: 'convenience' // コンビニユーザー
            },
            agreement: false, // 利用規約に同意したかどうか
            errors: null, // エラーメッセージ
            PasswordType: 'password', // パスワードの初期設定
            PasswordConfirmType: 'password', // パスワード（再入力）の初期設定
            PasswordIconClass: 'far fa-eye-slash', // 初期アイコン
            PasswordConfirmIconClass: 'far fa-eye-slash', // 初期アイコン
        };
    },

    validations: { // フロント側のバリデーション
        formData: {
            convenience_name: {
                required,
                maxLength: maxLength(255),
            },
            branch_name: {
                required,
                maxLength: maxLength(255),
            },
            prefecture: {
                required,
                maxLength: maxLength(255),
            },
            city: {
                required,
                maxLength: maxLength(255),
            },
            town: {
                required,
                maxLength: maxLength(255),
            },
            building: {
                required,
                maxLength: maxLength(255),
            },
            email: {
                required,
                email,
                maxLength: maxLength(255),
            },
            password: {
                required,
                validPasswordFormat,
                minLength: minLength(8),
            },
            password_confirmation: {
                required,
                validPasswordFormat,
                minLength: minLength(8),
            },
        },
    },

    methods: {
        // 郵便番号検索APIを使って、郵便番号から住所を自動入力するメソッド
        searchAddress() {
            const postalcode = this.formData.postalcode; // 郵便番号フォームの入力値
            const zipCode = postalcode;
            // 郵便番号検索APIに郵便番号フォームの入力値を使ってGETリクエスト送信
            axios.get(`https://api.zipaddress.net/?zipcode=${zipCode}`, { adapter: jsonpAdapter }).then(rs => {
                // APIから返されたレスポンスデータを各入力フォームにセット
                const response = rs.data;
                this.formData.prefecture = response.pref; // 都道府県
                this.formData.city = response.city; // 市区町村
                this.formData.town = response.town; // 地名・番地
                this.formData.building = response.building; // 建物名・部屋番号
            }).catch(error => {
                console.error('住所検索エラー:', error);
            });
        },

        // 入力された値をサーバー側に送信するメソッド
        submitForm() {
            this.formData.agreement = this.agreement;  // 利用規約の同意をformDataに追加
            // ユーザー登録APIをPOST送信
            axios.post('/api/convenience/register', this.formData).then(response => { // フォームデータを含むリクエスト
                this.$router.push({ name: 'convenience.login' }); // ユーザー登録後、ログインページに遷移
            }).catch(error => {
                this.errors = error.response.data.errors;
            });
        },

        // パスワードの表示・非表示を切り替えるメソッド
        togglePasswordVisibility(type) {
            if (type === 'password') { // パスワードの入力フォーム
                this.PasswordType = this.PasswordType === 'password' ? 'text' : 'password'; // PasswordTypeによってパスワードの表示・非表示を切り替え
                this.PasswordIconClass = this.PasswordIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash'; // PasswordIconClassによってパスワードのアイコンを切り替え
            } else if (type === 'password_confirm') { // パスワード（再入力）の入力フォーム
                this.PasswordConfirmType = this.PasswordConfirmType === 'password' ? 'text' : 'password'; // PasswordTypeによってパスワードの表示・非表示を切り替え
                this.PasswordConfirmIconClass = this.PasswordConfirmIconClass === 'far fa-eye-slash' ? 'far fa-eye' : 'far fa-eye-slash'; // PasswordIconClassによってパスワードのアイコンを切り替え
            }
        }
    }
}
</script>