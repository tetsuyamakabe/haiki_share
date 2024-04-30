import Vue from 'vue';
import VueRouter from 'vue-router'; // VueRouterのimport

// 共通コンポーネント
import HomeComponent from "./components/HomeComponent"; // HOME画面
import TermsComponent from './components/TermsComponent.vue'; // 利用規約ページ

// 利用者側
import UserRegisterComponent from './components/User/RegisterComponent.vue'; // ユーザー登録画面
import UserLoginComponent from './components/User/LoginComponent.vue'; // ログイン画面
import UserLogoutComponent from './components/User/LogoutComponent.vue'; // ログアウト画面
import UserMyPageComponent from './components/User/MyPageComponent.vue'; // マイページ画面
import UserForgotPasswordComponent from './components/User/ForgotPasswordComponent.vue'; // パスワードメール送信画面
import UserResetPasswordPasswordComponent from './components/User/ResetPasswordComponent.vue'; // パスワードリセット画面
import UserProfileComponent from './components/User/ProfileComponent.vue'; // プロフィール編集画面
import UserWithdrawComponent from './components/User/WithdrawComponent.vue'; // 退会画面
import UserProductDetailComponent from './components/User/ProductDetailComponent.vue'; // 商品詳細画面

// コンビニ側
import ConvenienceRegisterComponent from './components/Convenience/RegisterComponent.vue'; // ユーザー登録画面
import ConvenienceLoginComponent from './components/Convenience/LoginComponent.vue' // ログイン画面
import ConvenienceLogoutComponent from './components/Convenience/LogoutComponent.vue'; // ログアウト画面
import ConvenienceMyPageComponent from './components/Convenience/MyPageComponent.vue' // マイページ画面
import ConvenienceForgotPasswordComponent from './components/Convenience/ForgotPasswordComponent.vue'; // パスワードメール送信画面
import ConvenienceResetPasswordPasswordComponent from './components/Convenience/ResetPasswordComponent.vue' // パスワードリセット画面
import ConvenienceProfileComponent from './components/Convenience/ProfileComponent.vue'; // プロフィール編集画面
import ConvenienceWithdrawComponent from './components/Convenience/WithdrawComponent.vue'; // 退会画面
import ConvenienceProductDetailComponent from './components/Convenience/ProductDetailComponent.vue'; // 商品詳細画面

// 商品
import ProductSaleComponent from './components/Convenience/ProductSaleComponent.vue'; // 商品出品画面
import ProductEditComponent from './components/Convenience/ProductEditComponent.vue'; // 商品編集画面
import ProductSaleIndexComponent from './components/Convenience/ProductSaleIndexComponent.vue'; // 出品した商品一覧画面
import ProductPurchaseIndexComponent from './components/Convenience/ProductPurchaseIndexComponent.vue'; // 購入された商品一覧画面
import ProductAllIndexComponent from './components/ProductAllIndexComponent.vue'; // 商品一覧画面

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
        // 利用規約ページ
        {
            path: '/terms',
            name: 'terms',
            component: TermsComponent
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
        // 利用者側ログアウト画面
        {
            path: '/user/logout',
            name: 'user.logout',
            component: UserLogoutComponent
        },
        // コンビニ側ログアウト画面
        {
            path: '/convenience/logout',
            name: 'convenience.logout',
            component: ConvenienceLogoutComponent
        },
        // 利用者側マイページ画面
        {
            path: '/user/mypage',
            name: 'user.mypage',
            component: UserMyPageComponent
        },
        // コンビニ側マイページ画面
        {
            path: '/convenience/mypage',
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
            path: '/user/mypage/profile',
            name: 'user.profile',
            component: UserProfileComponent,
            props: true,
        },
        // コンビニ側プロフィール編集画面
        {
            path: '/convenience/mypage/profile',
            name: 'convenience.profile',
            component: ConvenienceProfileComponent,
            props: true,
        },
        // 利用者側退会画面
        {
            path: '/user/mypage/withdraw',
            name: 'user.withdraw',
            component: UserWithdrawComponent,
            props: true,
        },
        // コンビニ側退会画面
        {
            path: '/convenience/mypage/withdraw',
            name: 'convenience.withdraw',
            component: ConvenienceWithdrawComponent,
            props: true,
        },
        // 商品出品画面
        {
            path: '/convenience/products/create',
            name: 'convenience.products.create',
            component: ProductSaleComponent,
        },
        // 商品編集画面
        {
            path: '/convenience/products/edit/:productId',
            name: 'convenience.products.edit',
            component: ProductEditComponent,
        },
        // 出品した商品一覧画面
        {
            path: '/convenience/products/sale',
            name: 'convenience.products.sale',
            component: ProductSaleIndexComponent,
        },
        // 購入された商品一覧画面
        {
            path: '/convenience/products/purchase',
            name: 'convenience.products.purchase',
            component: ProductPurchaseIndexComponent,
        },
        // 商品一覧画面
        {
            path: '/products',
            name: 'products',
            component: ProductAllIndexComponent,
        },
        // 利用者側商品詳細画面
        {
            path: '/user/products/detail/:productId',
            name: 'user.products.detail',
            component: UserProductDetailComponent,
        },
        // コンビニ側商品詳細画面
        {
            path: '/convenience/products/detail/:productId',
            name: 'convenience.products.detail',
            component: ConvenienceProductDetailComponent,
        },
    ]
});

export default router;