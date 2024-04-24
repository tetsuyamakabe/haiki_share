import VueRouter from 'vue-router'; // VueRouterのimport

// 共通コンポーネント
import HomeComponent from "./components/HomeComponent"; // HOME画面

// 利用者側
import UserRegisterComponent from './components/User/RegisterComponent.vue'; // ユーザー登録画面
import UserLoginComponent from './components/User/LoginComponent.vue'; // ログイン画面
import UserMyPageComponent from './components/User/MyPageComponent.vue'; // マイページ画面
import UserForgotPasswordComponent from './components/User/ForgotPasswordComponent.vue'; // パスワードメール送信画面
import UserResetPasswordPasswordComponent from './components/User/ResetPasswordComponent.vue'; // パスワードリセット画面
import UserProfileComponent from './components/User/ProfileComponent.vue'; // プロフィール編集画面
import UserWithdrawComponent from './components/User/WithdrawComponent.vue'; // 退会画面

// コンビニ側
import ConvenienceRegisterComponent from './components/Convenience/RegisterComponent.vue'; // ユーザー登録画面
import ConvenienceLoginComponent from './components/Convenience/LoginComponent.vue' // ログイン画面
import ConvenienceMyPageComponent from './components/Convenience/MyPageComponent.vue' // マイページ画面
import ConvenienceForgotPasswordComponent from './components/Convenience/ForgotPasswordComponent.vue'; // パスワードメール送信画面
import ConvenienceResetPasswordPasswordComponent from './components/Convenience/ResetPasswordComponent.vue' // パスワードリセット画面
import ConvenienceProfileComponent from './components/Convenience/ProfileComponent.vue'; // プロフィール編集画面
import ConvenienceWithdrawComponent from './components/Convenience/WithdrawComponent.vue'; // 退会画面

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        // HOME画面
        {
            path: '/home',
            name: 'home',
            component: HomeComponent
        },
        // 利用者側ユーザー登録画面
        {
            path: '/user/register',
            name: 'user.register',
            component: UserRegisterComponent
        },
        // コンビニ側ユーザー登録画面
        {
            path: '/convenience/register',
            name: 'convenience.register',
            component: ConvenienceRegisterComponent
        },
        // 利用者側ログイン画面
        {
            path: '/user/login',
            name: 'user.login',
            component: UserLoginComponent
        },
        // コンビニ側ログイン画面
        {
            path: '/convenience/login',
            name: 'convenience.login',
            component: ConvenienceLoginComponent
        },
        // 利用者側マイページ画面
        {
            path: '/user/mypage/:userId',
            name: 'user.mypage',
            component: UserMyPageComponent
        },
        // コンビニ側マイページ画面
        {
            path: '/convenience/mypage/:userId',
            name: 'convenience.mypage',
            component: ConvenienceMyPageComponent
        },
        // 利用者側パスワードメール送信画面
        {
            path: '/user/password/email',
            name: 'user.password.email',
            component: UserForgotPasswordComponent
        },
        // コンビニ側パスワードメール送信画面
        {
            path: '/convenience/password/email',
            name: 'convenience.password.email',
            component: ConvenienceForgotPasswordComponent
        },
        // 利用者側パスワードリセット画面
        {
            path: '/user/password/reset/:token',
            name: 'user.password.reset',
            component: UserResetPasswordPasswordComponent
        },
        // コンビニ側パスワードリセット画面
        {
            path: '/convenience/password/reset/:token',
            name: 'convenience.password.reset',
            component: ConvenienceResetPasswordPasswordComponent
        },
        // 利用者側プロフィール編集画面
        {
            path: '/user/mypage/profile/:userId',
            name: 'user.profile',
            component: UserProfileComponent,
            props: true,
        },
        // コンビニ側プロフィール編集画面
        {
            path: '/convenience/mypage/profile/:userId',
            name: 'convenience.profile',
            component: ConvenienceProfileComponent,
            props: true,
        },
        // 利用者側退会画面
        {
            path: '/user/mypage/withdraw/:userId',
            name: 'user.withdraw',
            component: UserWithdrawComponent,
            props: true,
        },
        // コンビニ側退会画面
        {
            path: '/convenience/mypage/withdraw/:userId',
            name: 'convenience.withdraw',
            component: ConvenienceWithdrawComponent,
            props: true,
        },

    ]
});

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

// UI部分コンポーネント
Vue.component('header-component', require('./components/HeaderComponent.vue').default); // ヘッダー
// Vue.component('terms-component', require('./components/TermsComponent.vue').default); // 利用規約


// // 利用者側・コンビニ側共通
// Vue.component('home-component', require('./components/HomeComponent.vue').default); //商品一覧画面

// Vue.component('product-allindex-component', require('./components/ProductAllIndexComponent.vue').default); //商品一覧画面


// // 利用者側
// // Vue.component('user-register-component', require('./components/User/RegisterComponent.vue').default); // ユーザー登録画面
// // Vue.component('user-login-component', require('./components/User/LoginComponent.vue').default); // ログイン画面
// // Vue.component('user-mypage-component', require('./components/User/MyPageComponent.vue').default); // マイページ画面
// // Vue.component('user-forgotpassword-component', require('./components/User/ForgotPasswordComponent.vue').default); // パスワード変更（メールアドレス入力）画面
// // Vue.component('user-resetpassword-component', require('./components/User/ResetPasswordComponent.vue').default); // パスワード変更（古いパスワード・新しいパスワード入力）画面
// // Vue.component('user-profile-component', require('./components/User/ProfileComponent.vue').default); // プロフィール編集画面
// // Vue.component('user-withdraw-component', require('./components/User/WithdrawComponent.vue').default); // 退会画面
// // Vue.component('user-productdetail-component', require('./components/User/ProductDetailComponent.vue').default); //商品詳細画面

// // // コンビニ側
// // Vue.component('convenience-register-component', require('./components/Convenience/RegisterComponent.vue').default); // ユーザー登録画面
// // Vue.component('convenience-login-component', require('./components/Convenience/LoginComponent.vue').default); // ログイン画面
// // Vue.component('convenience-mypage-component', require('./components/Convenience/MyPageComponent.vue').default); // マイページ画面
// // Vue.component('convenience-forgotpassword-component', require('./components/Convenience/ForgotPasswordComponent.vue').default); // パスワード変更（メールアドレス入力）画面
// // Vue.component('convenience-resetpassword-component', require('./components/Convenience/ResetPasswordComponent.vue').default); // パスワード変更（古いパスワード・新しいパスワード入力）画面
// // Vue.component('convenience-profile-component', require('./components/Convenience/ProfileComponent.vue').default); // プロフィール編集画面
// // Vue.component('convenience-withdraw-component', require('./components/Convenience/WithdrawComponent.vue').default); // 退会画面
// // Vue.component('convenience-productindex-component', require('./components/Convenience/ProductIndexComponent.vue').default); //商品一覧画面
// // Vue.component('convenience-productsale-component', require('./components/Convenience/ProductSaleComponent.vue').default); //商品出品画面
// // Vue.component('convenience-productedit-component', require('./components/Convenience/ProductEditComponent.vue').default); //商品編集画面
// // Vue.component('convenience-productdelete-component', require('./components/Convenience/ProductDeleteComponent.vue').default); //商品削除ボタン
// // Vue.component('convenience-productdetail-component', require('./components/Convenience/ProductDetailComponent.vue').default); //商品詳細画面

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});