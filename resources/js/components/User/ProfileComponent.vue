<template>
    <main class="l-main">
        <section class="l-main__wrapper">
            <h1 class="c-title u-mb__xl">利用者プロフィール編集</h1>
            <form @submit.prevent="submitForm" class="c-form">

                <!-- お名前 -->
                <label for="name" class="c-label">お名前<span class="c-required">必須</span></label>
                <span v-if="errors && errors.name" class="c-error u-mt__s">{{ errors.name[0] }}</span>
                <input v-model="formData.name" id="name" type="name" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.name }" autocomplete="name">

                <!-- メールアドレス -->
                <label for="email" class="c-label">メールアドレス<span class="c-required">必須</span></label>
                <span v-if="errors && errors.email" class="c-error u-mt__s">{{ errors.email[0] }}</span>
                <input v-model="formData.email" id="email" type="text" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">

                <!-- パスワード -->
                <label for="password" class="c-label">パスワード<span class="c-required">必須</span></label>
                <span v-if="errors && errors.password" class="c-error u-mt__s">{{ errors.password[0] }}</span>
                <div class="c-input__password">
                    <input v-model="formData.password" id="password" :type="PasswordType" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.password }" placeholder="英数字8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('password')"><i :class="PasswordIconClass"></i></span>
                </div>

                <!-- パスワード（再入力） -->
                <label for="password-confirm" class="c-label">パスワード（再入力）<span class="c-required">必須</span></label>
                <span v-if="errors && errors.password_confirmation" class="c-error u-mt__s">{{ errors.password_confirmation[0] }}</span>
                <div class="c-input__password">
                    <input v-model="formData.password_confirmation" id="password-confirm" :type="PasswordConfirmType" class="c-input u-pd__s u-mt__m u-mb__m" :class="{ 'is-invalid': errors && errors.password_confirmation }" placeholder="英数字8文字以上で入力してください">
                    <span @click="togglePasswordVisibility('password_confirm')"><i :class="PasswordConfirmIconClass"></i></span>
                </div>

                <!-- 自己紹介文 -->
                <label for="introduction" class="c-label">自己紹介</label>
                <span v-if="errors && errors.introduction" class="c-error u-mt__s">{{ errors.introduction[0] }}</span>
                <div class="p-textarea__form">
                    <textarea v-model="formData.introduction" maxlength="50" id="introduction" type="text" class="c-textarea u-pd__s u-mt__m u-mb__m" autocomplete="introduction" @keyup="countCharacters" :class="{ 'is-invalid': errors && errors.introduction }" placeholder="50文字以内で入力してください"></textarea>
                    <span class="c-textarea__count">{{ formData.introduction.length }} / 50文字</span>
                </div>

                <!-- 顔写真 -->
                <label for="profile-icon" class="c-label">顔写真</label>
                <span v-if="errors && errors.icon" class="c-error u-mt__s">{{ errors.icon[0] }}</span>
                <div class="p-profile__icon p-profile__icon--container u-pd__s" @drop="handleDrop" :class="{ 'is-invalid': errors && errors.icon }">
                    <input type="file" id="profile-icon" @change="handleFileChange" class="c-input__hidden">
                    <img v-if="!iconPreview && formData.icon" :src="'/storage/icons/' + formData.icon" alt="アップロード顔写真" class="p-profile__icon">
                    <img v-else-if="iconPreview" :src="iconPreview" alt="アップロード顔写真" class="p-profile__icon">
                    <img v-else src="/default.png" alt="デフォルト顔写真" class="p-profile__icon">
                </div>

                <!-- 更新ボタン -->
                <button type="submit" class="c-button c-button__submit c-button__user u-pd__s u-mt__m">プロフィールを更新する</button>

            </form>
        </section>
        <a @click="$router.back()" class="c-link c-link__back u-mt__s u-mb__s">前のページに戻る</a>
    </main>
</template>

<script>
import axios from '../../axiosErrorHandler';

export default {
    data() {
        return {
            formData: {
                name: '', // お名前
                email: '', // メールアドレス
                password: '', // パスワード
                password_confirmation: '', // パスワード（再入力）
                introduction: '', // 自己紹介文
                icon: '', // 顔写真
            },
            textareaCount: 0, // 自己紹介文の文字数カウント初期値
            iconPreview: '', // アイコン画像のプレビュー
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
                this.formData.icon = this.user.icon || ''; // 顔写真
            }).catch (error => {
                console.error('プロフィール取得失敗:', error.response.data);
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
            if (this.formData.icon !== '') {
                formData.append('icon', this.formData.icon); // 顔写真
            }
            // 利用者側プロフィール情報更新APIをPOST送信
            axios.post('/api/user/mypage/profile', formData, config).then(response => { // リクエストヘッダとフォームデータを含むリクエスト
                this.$router.push({ name: 'user.mypage' }); // プロフィール更新完了後、マイページに遷移する
            }).catch(error => {
                console.log('errorは、', error);
                console.error('プロフィール編集失敗:', error.response.data);
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
                this.formData.icon = file; // formData.iconにファイルオブジェクトを設定
            } else {
                this.formData.icon = null; // ファイルがない場合はnull
            }
        },

        // 画像のプレビューを表示するメソッド
        previewImage(file) {
            const reader = new FileReader(); // FileReaderオブジェクトの作成
            reader.onload = (e) => { // 画像の読み込み
                this.iconPreview = e.target.result; // プレビュー画像のURLを生成し、formDataに設定
            };
            reader.readAsDataURL(file); // ファイルをデータURLとして読み込み
        }
    }
}
</script>