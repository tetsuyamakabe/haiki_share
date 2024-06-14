<template>
    <main class="l-main">
        <div class="l-main__header">
            <h1 class="c-title">コンビニプロフィール編集</h1>
        </div>
        <div class="l-article">
            <div class="l-article__main">
                <form @submit.prevent="submitForm" class="c-form">
                    <!-- コンビニ名 -->
                    <label for="convenience_name" class="c-label">コンビニ名<span class="c-badge">必須</span></label>
                    <span v-if="errors && errors.convenience_name" class="c-error">{{ errors.convenience_name[0] }}</span>
                    <input v-model="formData.convenience_name" id="convenience_name" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.convenience_name }" autocomplete="convenience_name">
                    <!-- 支店名 -->
                    <label for="branch_name" class="c-label">支店名<span class="c-badge">必須</span></label>
                    <span v-if="errors && errors.branch_name" class="c-error">{{ errors.branch_name[0] }}</span>
                    <input v-model="formData.branch_name" id="branch_name" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.branch_name }" autocomplete="branch_name">
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
                    <input v-model="formData.prefecture" id="prefecture" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.prefecture }" autocomplete="prefecture">
                    <!-- 市区町村 -->
                    <label for="city" class="c-label">市区町村<span class="c-badge">必須</span></label>
                    <span v-if="errors && errors.city" class="c-error">{{ errors.city[0] }}</span>
                    <input v-model="formData.city" id="city" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.city }" autocomplete="city">
                    <!-- 地名・番地 -->
                    <label for="town" class="c-label">地名・番地<span class="c-badge">必須</span></label>
                    <span v-if="errors && errors.town" class="c-error">{{ errors.town[0] }}</span>
                    <input v-model="formData.town" id="town" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.town }" autocomplete="town">
                    <!-- 建物名・部屋番号 -->
                    <label for="building" class="c-label">建物名・部屋番号</label>
                    <span v-if="errors && errors.building" class="c-error">{{ errors.building[0] }}</span>
                    <input v-model="formData.building" id="building" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.building }" autocomplete="building">
                    <!-- メールアドレス -->
                    <label for="email" class="c-label">メールアドレス<span class="c-badge">必須</span></label>
                    <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
                    <input v-model="formData.email" id="email" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">
                    <!-- パスワード -->
                    <label for="password" class="c-label">パスワード</label>
                    <span class="c-text c-text--note u-fz-10@sm">※パスワードとパスワード（再入力）は、半角数字・英字大文字・小文字、記号（!@#$%^&*）を使って8文字以上で入力してください</span>
                    <span v-if="errors && errors.password" class="c-error">{{ errors.password[0] }}</span>
                    <div class="c-password">
                        <input v-model="formData.password" id="password" :type="PasswordType" class="c-input" :class="{ 'is-invalid': errors && errors.password }" placeholder="8文字以上で入力してください">
                        <span @click="togglePasswordVisibility('password')" class="c-password__icon">
                            <i :class="PasswordIconClass"></i>
                        </span>
                    </div>
                    <!-- パスワード（再入力） -->
                    <label for="password-confirm" class="c-label">パスワード（再入力）</label>
                    <span v-if="errors && errors.password_confirmation" class="c-error">{{ errors.password_confirmation[0] }}</span>
                    <div class="c-password">
                        <input v-model="formData.password_confirmation" id="password-confirm" :type="PasswordConfirmType" class="c-input" :class="{ 'is-invalid': errors && errors.password_confirmation }" placeholder="8文字以上で入力してください">
                        <span @click="togglePasswordVisibility('password_confirm')" class="c-password__icon">
                            <i :class="PasswordConfirmIconClass"></i>
                        </span>
                    </div>
                    <!-- 自己紹介 -->
                    <label for="introduction" class="c-label">自己紹介</label>
                    <span v-if="errors && errors.introduction" class="c-error">{{ errors.introduction[0] }}</span>
                    <textarea v-model="formData.introduction" maxlength="50" id="introduction" type="text" class="c-textarea" autocomplete="introduction" @keyup="countCharacters" :class="{ 'is-invalid': errors && errors.introduction }" placeholder="50文字以内で入力してください"></textarea>
                    <span class="c-textarea--count">{{ formData.introduction.length }} / 50文字</span>
                    <!-- 顔写真 -->
                    <label for="avatar" class="c-label">顔写真</label>
                    <span v-if="errors && errors.avatar" class="c-error">{{ errors.avatar[0] }}</span>
                    <div class="c-avatar" @drop="handleDrop" :class="{ 'is-invalid': errors && errors.avatar }">
                        <input type="file" id="avatar" @change="handleFileChange" class="c-input--hidden">
                        <img v-if="!avatarPreview && formData.avatar" :src="formData.avatar" alt="アップロード顔写真" class="c-avatar--img">
                        <img v-else-if="avatarPreview" :src="avatarPreview" alt="アップロード顔写真" class="c-avatar--img">
                        <img v-else :src="'https://haikishare.com/avatar/default.png'" alt="デフォルト顔写真" class="c-avatar--img">
                    </div>
                    <!-- 更新ボタン -->
                    <button type="submit" class="c-button c-button--submit c-button--main">プロフィールを更新する</button>
                </form>
                <router-link class="c-link c-link--withdraw" :to="{ name: 'convenience.withdraw' }">退会はこちら</router-link>
            </div>
            <!-- サイドバー -->
            <sidebar-component :convenience_name="convenience_name" :branch_name="branch_name" :prefecture="prefecture" :city="city" :town="town" :building="building" :introduction="introduction"></sidebar-component>
        </div>
    </main>
</template>

<script>
const jsonpAdapter = require('axios-jsonp');
import SidebarComponent from './SidebarComponent.vue'; // サイドバーコンポーネント

export default {
    components: {
        SidebarComponent // サイドバーコンポーネントを読み込み
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
                introduction: '', // 自己紹介文
                avatar: '', // 顔写真
            },
            avatarPreview: '', // アイコン画像のプレビュー
            convenience_name: '', // コンビニ名
            branch_name: '', // 支店名
            prefecture: '', // 都道府県
            city: '', // 市区町村
            town: '', // 地名・番地
            building: '', // 建物名・部屋番号
            introduction: '', // 自己紹介文
            errors: null, // エラーメッセージ
            PasswordType: 'password', // パスワードの初期設定
            PasswordConfirmType: 'password', // パスワード（再入力）の初期設定
            PasswordIconClass: 'far fa-eye-slash', // 初期アイコン
            PasswordConfirmIconClass: 'far fa-eye-slash', // 初期アイコン
        };
    },

    computed: {
        // ログインユーザーかどうか
        isLogin() {
            return this.$store.getters['auth/check'];
        },
    },

    created() {
        this.getProfile(); // インスタンス初期化時に編集前のプロフィール情報を読み込む
        this.getSidebarProfile(); // インスタンス初期化時にサイドバーに表示するプロフィール情報を読み込む
    },

    methods: {
        // 編集前のプロフィール情報をサーバーから取得
        getProfile() {
            // コンビニ側プロフィール情報の取得APIをGET送信
            axios.get('/api/convenience/mypage/profile').then(response => {
                // レスポンスデータをそれぞれのプロパティにセット
                this.user = response.data.user; // ユーザー情報
                this.convenience = response.data.convenience; // コンビニ情報
                this.address = response.data.address; // 住所情報
                // 取得した各プロフィール情報をformDataに入れる
                this.formData.convenience_name = this.user.name || ''; // コンビニ名
                this.formData.branch_name = this.convenience.branch_name || '', // 支店名
                this.formData.prefecture = this.address.prefecture || '', // 都道府県
                this.formData.city = this.address.city || '', // 市区町村
                this.formData.town = this.address.town || '', // 地名・番地
                this.formData.building = this.address.building || '', // 建物名・部屋番号
                this.formData.email = this.user.email || ''; // メールアドレス
                this.formData.password = ''; // 編集前のパスワードは非表示（入力フォームを空）にする
                this.formData.password_confirmation = ''; // 編集前のパスワード（再入力）は非表示（入力フォームを空）にする
                this.formData.introduction = this.user.introduction || ''; // 自己紹介文
                this.formData.avatar = this.user.avatar || ''; // 顔写真
            }).catch(error => {
                this.errors = error.response.data;
            });
        },

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
            // リクエストヘッダー定義
            const config = {
                headers: {
                    'content-type': 'multipart/form-data', // ファイルのアップロードを含むリクエストボディのデータ形式
                }
            };
            // フォームデータを作成
            const formData = new FormData(); // FormDataオブジェクトの作成
            formData.append('_method', 'PUT'); // リクエストメソッドをPUTにする
            formData.append('convenience_name', this.formData.convenience_name); // コンビニ名
            formData.append('branch_name', this.formData.branch_name); // 支店名
            formData.append('prefecture', this.formData.prefecture); // 都道府県
            formData.append('city', this.formData.city); // 市区町村
            formData.append('town', this.formData.town); // 地名・番地
            formData.append('building', this.formData.building); // 建物名・部屋番号
            formData.append('email', this.formData.email); // メールアドレス
            formData.append('password', this.formData.password); // パスワード
            formData.append('password_confirmation', this.formData.password_confirmation); // パスワード（再入力）
            formData.append('introduction', this.formData.introduction); // 自己紹介文
            // 顔写真がアップロードされている場合はフォームデータに追加
            if (this.formData.avatar !== '') {
                formData.append('avatar', this.formData.avatar); // 顔写真
            }
            // コンビニ側プロフィール情報更新APIをPOST送信
            axios.post('/api/convenience/mypage/profile', formData, config).then(response => { // リクエストヘッダとフォームデータを含むリクエスト
                this.$router.push({ name: 'convenience.mypage' }); // プロフィール更新完了後、マイページに遷移する
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
        },

        // 自己紹介文の文字数をカウントするメソッド
        countCharacters() {
            this.countCharactersLength = this.formData.introduction.length; // 文字数のカウント
            if (this.countCharactersLength > 50) { // 文字数が50文字を超えているか
                this.formData.introduction = this.formData.introduction.slice(0, 50); // 文字列を50文字まで切り抜く
                this.countCharactersLength = 50; // 50文字以上の文字数を制限する
            }
        },

        // ドラッグ＆ドロップエリアに画像がドロップされたときの処理
        handleDrop(event) {
            event.preventDefault(); // デフォルトの動作をキャンセル
            event.dataTransfer.files[0]; // 最初のファイルを取得
        },

        // ファイルが選択されたときの処理
        handleFileChange(event) {
            const file = event.target.files[0]; // 最初のファイルを取得
            if (file) {
                this.previewImage(file); // プレビューを表示する
                this.formData.avatar = file; // formData.avatarにファイルオブジェクトを設定
            } else {
                this.formData.avatar = null; // ファイルがない場合はnull
            }
        },

        // 画像のプレビューを表示するメソッド
        previewImage(file) {
            const reader = new FileReader(); // FileReaderオブジェクトの作成
            reader.onload = (e) => { // 画像の読み込み
                this.avatarPreview = e.target.result; // プレビュー画像のURLを生成し、formDataに設定
            };
            reader.readAsDataURL(file); // ファイルをデータURLとして読み込み
        },

        // サイドバーに表示するプロフィール情報の取得
        getSidebarProfile() {
            // コンビニ側プロフィール情報の取得APIをGET送信
            axios.get('/api/convenience/mypage/profile').then(response => {
                // レスポンスデータをそれぞれのプロパティにセット
                this.user = response.data.user; // ユーザー情報
                this.convenience = response.data.convenience; // コンビニ情報
                this.address = response.data.address; // 住所情報
                // 取得した各プロフィール情報をそれぞれのプロパティにセット
                this.convenience_name = this.user.name; // コンビニ名
                this.branch_name = this.convenience.branch_name; // 支店名
                this.prefecture = this.address.prefecture; // 住所
                this.city = this.address.city; // 市区町村
                this.town = this.address.town; // 地名・番地
                this.building = this.address.building; // 建物名・部屋番号
                this.introduction = this.user.introduction; // 自己紹介文
            }).catch(error => {
                this.errors = error.response.data;
            });
        }
    }
}
</script>