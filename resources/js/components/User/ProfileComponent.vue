<template>
    <main class="l-main">
        <div class="p-profile">
            <div class="p-profile__form">
                <h1 class="c-title">プロフィール編集</h1>
                <div class="p-container">
                    <form @submit.prevent="submitForm">

                        <!-- バリデーションエラーメッセージ -->
                        <span v-if="errors && errors.name" class="c-error">{{ errors.name[0] }}</span>
                        <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
                        <span v-if="errors && errors.password" class="c-error">{{ errors.password[0] }}</span>
                        <span v-if="errors && errors.password_confirmation" class="c-error">{{ errors.password_confirmation[0] }}</span>
                        <span v-if="errors && errors.introduction" class="c-error">{{ errors.introduction[0] }}</span>
                        <span v-if="errors && errors.icon" class="c-error">{{ errors.icon[0] }}</span>

                        <table>
                            <tr>
                                <th><label for="name" class="c-label">お名前</label></th>
                                <td>
                                    <input v-model="formData.name" id="name" type="text" class="c-input" :class="{ 'is-invalid': errors && errors.name }" autocomplete="name">
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
                        <div class="p-profile__button">
                            <button type="submit" class="c-button">更新する</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            formData: {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                introduction: '',
                icon: '',
            },
            textareaCount: 0,
            iconPreview: '',
            errors: null,
            PasswordType: 'password',
            PasswordConfirmType: 'password',
            PasswordIconClass: 'far fa-eye-slash',
            PasswordConfirmIconClass: 'far fa-eye-slash',
        };
    },

    created() {
        this.userId = this.$route.params.userId; // ルートからuserIdを取得
        this.getProfile(); // プロフィール情報を取得
    },

    methods: {
        // 編集前のプロフィール情報をサーバーから取得
        getProfile() {
            console.log('プロフィールを取得します');
            axios.get('/api/user/mypage/profile/' + this.userId).then(response => {
                this.user = response.data.user;
                console.log('APIからのレスポンス:', response.data);
                this.formData.name = this.user.name || '';
                this.formData.email = this.user.email || '';
                this.formData.password = '';
                this.formData.password_confirmation = '';
                this.formData.introduction = this.user.introduction || '';
                this.formData.icon = this.user.icon || '';
            }).catch(error => {
                console.error('プロフィール取得失敗:', error.response.data);
                this.errors = error.response.data;
            });
        },

        // 入力された値をサーバー側に送信するメソッド
        submitForm() {
            const userId = this.user.id;

            // リクエストヘッダー定義
            const config = {
                headers: {
                'content-type': 'multipart/form-data'
                }
            };

            // フォームデータを作成
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', this.formData.name);
            formData.append('email', this.formData.email);
            formData.append('password', this.formData.password);
            formData.append('password_confirmation', this.formData.password_confirmation);
            formData.append('introduction', this.formData.introduction);
            
            // icon フィールドが空でない場合のみ、フォームデータに追加
            if (this.formData.icon !== '') {
                formData.append('icon', this.formData.icon);
            }

            axios.post('/api/user/mypage/profile/' + userId, formData, config).then(response => {
                this.message = response.data.message;
                console.log('this.messageは、', this.message);
                console.log('プロフィールを更新します。');
                this.$router.push({ name: 'user.mypage', params: { userId: userId } }); // userIdを含むマイページに遷移
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