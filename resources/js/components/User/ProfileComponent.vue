<template>
    <div class="p-container">
        <form @submit.prevent="submitForm">

            <!-- バリデーションエラーメッセージ -->
            <span v-if="errors && errors.name" class="c-error">{{ errors.name[0] }}</span>
            <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
            <span v-if="errors && errors.password" class="c-error">{{ errors.password[0] }}</span>
            <span v-if="errors && errors.password_confirmation" class="c-error">{{ errors.password_confirmation[0] }}</span>

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
                    <th><label for="icon" class="c-label">顔写真</label></th>
                    <td>
                        <div class="drag-drop-area" @drop="handleDrop">
                            <input type="file" id="icon" @change="handleFileChange" class="hidden-input">
                            <div class="drag-drop-content">
                                <img src="/default.png" alt="デフォルト顔写真" class="c-icon">
                                <span v-if="!formData.icon">ドラッグ＆ドロップ</span>
                            </div>
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
</template>

<script>
import axios from 'axios';

export default {
    props: ['user'],
    data() {
        return {
            formData: {
                name: this.user.name || '',
                email: this.user.email || '',
                password: '', // 編集前のパスワードは非表示（入力フォームを空）にする
                password_confirmation: '', // 編集前のパスワード（再入力）は非表示（入力フォームを空）にする
                introduction: this.user.introduction || '',
                icon: this.user.icon || '',
            },
            errors: null,
            PasswordType: 'password', // パスワードの初期設定
            PasswordConfirmType: 'password', // パスワード（再入力）の初期設定
            PasswordIconClass: 'far fa-eye-slash', // 初期アイコン
            PasswordConfirmIconClass: 'far fa-eye-slash', // 初期アイコン
        };
    },

    methods: {
        // 入力された値をサーバー側に送信するメソッド
        submitForm() {
            const userId = this.user.id; // Vue.jsコンポーネントから渡されたユーザーIDを取得する

            axios.put('/user/mypage/profile/' + userId, this.formData).then(response => {
                console.log('プロフィールが更新されました:', response.data);
                window.location.href = '/user/mypage/' + userId;
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

        // ドラッグ＆ドロップエリアに画像がドロップされたときの処理
        handleDrop(event) {
            event.preventDefault();
            const file = event.dataTransfer.files[0];
            this.displayImage(file);
        },

        // ファイルが選択されたときの処理
        handleFileChange(event) {
            console.log('handleFileChangeメソッドです');
            const file = event.target.files[0];
            if (file) {
                this.formData.icon = file;
            }
        }
    }
}
</script>