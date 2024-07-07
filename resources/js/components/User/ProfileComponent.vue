<template>
    <main class="l-main">
        <div class="l-main__header">
            <h1 class="c-title">利用者プロフィール編集</h1>
        </div>
        <div class="l-article">
            <div class="l-article__main">
                <form @submit.prevent="submitForm" class="c-form">
                    <!-- お名前 -->
                    <label for="name" class="c-label">お名前<span class="c-badge">必須</span></label>
                    <span v-if="errors && errors.name" class="c-error">{{ errors.name[0] }}</span>
                    <span v-if="$v.formData.name.$error && $v.formData.name.$dirty" class="c-error">お名前が入力されていません。</span>
                    <span v-if="$v.formData.name.$error && !$v.formData.name.maxLength && $v.formData.name.$dirty" class="c-error">お名前は、255文字以内で入力してください。</span>
                    <input v-model="formData.name" id="name" type="text" class="c-input" @blur="$v.formData.name.$touch()" :class="{'is-invalid': $v.formData.name.$error && $v.formData.name.$dirty, 'is-valid': !$v.formData.name.$error && $v.formData.name.$dirty}" autocomplete="name">

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

                    <!-- 自己紹介文 -->
                    <label for="introduction" class="c-label">自己紹介</label>
                    <span v-if="errors && errors.introduction" class="c-error">{{ errors.introduction[0] }}</span>
                    <span v-if="$v.formData.introduction.$error && !$v.formData.introduction.maxLength && $v.formData.introduction.$dirty" class="c-error">自己紹介は、50文字以内で入力してください。</span>
                    <textarea v-model="formData.introduction" maxlength="50" id="introduction" type="text" class="c-textarea" autocomplete="introduction" @keyup="countCharacters" @blur="$v.formData.introduction.$touch()" :class="{'is-invalid': $v.formData.introduction.$error && $v.formData.introduction.$dirty, 'is-valid': !$v.formData.introduction.$error && $v.formData.introduction.$dirty}" placeholder="50文字以内で入力してください"></textarea>
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
                <router-link class="c-link c-link--withdraw" :to="{ name: 'user.withdraw' }">退会はこちら</router-link>
            </div>
            <!-- サイドバー -->
            <sidebar-component :introduction="introduction"></sidebar-component>
        </div>
    </main>
</template>

<script>
import SidebarComponent from './SidebarComponent.vue'; // サイドバーコンポーネント
import { required, maxLength, email, minLength, helpers } from 'vuelidate/lib/validators'; // Vuelidateからバリデータをインポート
const validPasswordFormat = helpers.regex('validPasswordFormat', /^[a-zA-Z0-9!@#$%^&*]+$/); // パスワードとパスワード（再入力）の正規表現バリデーション

export default {
    components: {
        SidebarComponent // サイドバーコンポーネントを読み込み
    },

    data() {
        return {
            formData: {
                name: '', // お名前
                email: '', // メールアドレス
                password: '', // パスワード
                password_confirmation: '', // パスワード（再入力）
                introduction: '', // 自己紹介文
                avatar: '', // 顔写真
            },
            introduction: '', //自己紹介文
            avatarPreview: '', // アイコン画像のプレビュー
            errors: null, // エラーメッセージ
            PasswordType: 'password', // パスワードの初期設定
            PasswordConfirmType: 'password', // パスワード（再入力）の初期設定
            PasswordIconClass: 'far fa-eye-slash', // 初期アイコン
            PasswordConfirmIconClass: 'far fa-eye-slash', // 初期アイコン
        };
    },

    validations: { // フロント側のバリデーション
        formData: {
            name: {
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
            introduction: {
                maxLength: maxLength(50),
            },
        },
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
            // 利用者側プロフィール情報の取得APIをGET送信
            axios.get('/api/user/mypage/profile').then(response => {
                this.user = response.data.user; // レスポンスデータのユーザー情報をuserプロパティにセット
                // 取得した各プロフィール情報をformDataに入れる
                this.formData.name = this.user.name || ''; // お名前
                this.formData.email = this.user.email || ''; // メールアドレス
                this.formData.password = ''; // 編集前のパスワードは非表示（入力フォームを空）にする
                this.formData.password_confirmation = ''; // 編集前のパスワード（再入力）は非表示（入力フォームを空）にする
                this.formData.introduction = this.user.introduction || ''; // 自己紹介文
                this.formData.avatar = this.user.avatar || ''; // 顔写真
            }).catch (error => {
                this.errors = error.response.data;
            });
        },

        // 入力された値をサーバー側に送信するメソッド
        submitForm() {
            // リクエストヘッダー定義
            const config = {
                headers: {
                    'content-type': 'multipart/form-data' // ファイルのアップロードを含むリクエストボディのデータ形式
                }
            };
            // フォームデータを作成
            const formData = new FormData(); // FormDataオブジェクトの作成
            formData.append('_method', 'PUT'); // リクエストメソッドをPUTにする
            formData.append('name', this.formData.name); // お名前
            formData.append('email', this.formData.email); // メールアドレス
            formData.append('password', this.formData.password); // パスワード
            formData.append('password_confirmation', this.formData. password_confirmation); // パスワード（再入力）
            formData.append('introduction', this.formData.introduction); // 自己紹介文
            // 顔写真がアップロードされている場合はフォームデータに追加
            if (this.formData.avatar !== '') {
                formData.append('avatar', this.formData.avatar); // 顔写真
            }
            // 利用者側プロフィール情報更新APIをPOST送信
            axios.post('/api/user/mypage/profile', formData, config).then(response => { // リクエストヘッダとフォームデータを含むリクエスト
                this.$router.push({ name: 'user.mypage' }); // プロフィール更新完了後、マイページに遷移する
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
            // 利用者側プロフィール情報の取得APIをGET送信
            axios.get('/api/user/mypage/profile').then(response => {
                this.user = response.data.user; // レスポンスデータのユーザー情報をuserプロパティにセット
                // 取得した各プロフィール情報をintroductionプロパティにセット
                this.introduction = this.user.introduction; // 自己紹介文
            }).catch (error => {
                this.errors = error.response.data;
            });
        },
    }
}
</script>