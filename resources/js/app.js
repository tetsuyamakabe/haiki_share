/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

// 利用者側
Vue.component('user-register-component', require('./components/User/RegisterComponent.vue').default); // ユーザー登録画面
Vue.component('user-login-component', require('./components/User/LoginComponent.vue').default); // ログイン画面
Vue.component('user-forgotpassword-component', require('./components/User/ForgotPasswordComponent.vue').default); // パスワード変更（メールアドレス入力）画面
Vue.component('user-resetpassword-component', require('./components/User/ResetPasswordComponent.vue').default); // パスワード変更（古いパスワード・新しいパスワード入力）画面
Vue.component('user-profile-component', require('./components/User/ProfileComponent.vue').default); // プロフィール編集画面
Vue.component('user-withdraw-component', require('./components/User/WithdrawComponent.vue').default); // 退会画面
Vue.component('user-productindex-component', require('./components/User/ProductIndexComponent.vue').default); //商品一覧画面

// コンビニ側
Vue.component('convenience-register-component', require('./components/Convenience/RegisterComponent.vue').default); // ユーザー登録画面
Vue.component('convenience-login-component', require('./components/Convenience/LoginComponent.vue').default); // ログイン画面
Vue.component('convenience-forgotpassword-component', require('./components/Convenience/ForgotPasswordComponent.vue').default); // パスワード変更（メールアドレス入力）画面
Vue.component('convenience-resetpassword-component', require('./components/Convenience/ResetPasswordComponent.vue').default); // パスワード変更（古いパスワード・新しいパスワード入力）画面
Vue.component('convenience-profile-component', require('./components/Convenience/ProfileComponent.vue').default); // プロフィール編集画面
Vue.component('convenience-withdraw-component', require('./components/Convenience/WithdrawComponent.vue').default); // 退会画面
Vue.component('convenience-productindex-component', require('./components/Convenience/ProductIndexComponent.vue').default); //商品一覧画面
Vue.component('convenience-productsale-component', require('./components/Convenience/ProductSaleComponent.vue').default); //商品出品画面
Vue.component('convenience-productedit-component', require('./components/Convenience/ProductEditComponent.vue').default); //商品編集画面
Vue.component('convenience-productdelete-component', require('./components/Convenience/ProductDeleteComponent.vue').default); //商品削除ボタン
Vue.component('convenience-productdetail-component', require('./components/Convenience/ProductDetailComponent.vue').default); //商品詳細画面

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
