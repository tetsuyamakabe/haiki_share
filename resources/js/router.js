import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store';

// 共通コンポーネント
import TopComponent from "./components/Common/TopComponent.vue"; // TOP画面
import TermsComponent from './components/Common/TermsComponent.vue'; // 利用規約ページ
import PrivacyPolicyComponent from './components/Common/PrivacyPolicyComponent.vue'; // プライバシーポリシーページ
import ContactComponent from './components/Common/ContactComponent.vue'; // お問い合わせページ
import ErrorComponent from './components/Common/ErrorComponent.vue'; // エラーページ
import NotFoundComponent from './components/Common/NotFoundComponent.vue' // NotFoundページ
import ProductAllIndexComponent from './components/Common/ProductAllIndexComponent.vue'; // 商品一覧画面
import ProductDetailComponent from './components/Common/ProductDetailComponent.vue'; // 商品詳細画面

// 利用者側
import UserRegisterComponent from './components/User/RegisterComponent.vue'; // ユーザー登録画面
import UserLoginComponent from './components/User/LoginComponent.vue'; // ログイン画面
import UserMyPageComponent from './components/User/MyPageComponent.vue'; // マイページ画面
import UserForgotPasswordComponent from './components/User/ForgotPasswordComponent.vue'; // パスワードメール送信画面
import UserResetPasswordPasswordComponent from './components/User/ResetPasswordComponent.vue'; // パスワードリセット画面
import UserProfileComponent from './components/User/ProfileComponent.vue'; // プロフィール編集画面
import UserWithdrawComponent from './components/User/WithdrawComponent.vue'; // 退会画面
import UserProductDetailComponent from './components/User/ProductDetailComponent.vue'; // 商品詳細画面
import UserLikeProductComponent from './components/User/ProductLikeComponent.vue'; // お気に入り登録商品一覧画面
import UserPurchasedProductComponent from './components/User/ProductPurchasedComponent.vue'; // 購入した商品一覧画面

// コンビニ側
import ConvenienceRegisterComponent from './components/Convenience/RegisterComponent.vue'; // ユーザー登録画面
import ConvenienceLoginComponent from './components/Convenience/LoginComponent.vue' // ログイン画面
import ConvenienceMyPageComponent from './components/Convenience/MyPageComponent.vue' // マイページ画面
import ConvenienceForgotPasswordComponent from './components/Convenience/ForgotPasswordComponent.vue'; // パスワードメール送信画面
import ConvenienceResetPasswordPasswordComponent from './components/Convenience/ResetPasswordComponent.vue' // パスワードリセット画面
import ConvenienceProfileComponent from './components/Convenience/ProfileComponent.vue'; // プロフィール編集画面
import ConvenienceWithdrawComponent from './components/Convenience/WithdrawComponent.vue'; // 退会画面
import ConvenienceProductDetailComponent from './components/Convenience/ProductDetailComponent.vue'; // 商品詳細画面
import ConvenienceProductSaleComponent from './components/Convenience/ProductSaleComponent.vue'; // 商品出品画面
import ConvenienceProductEditComponent from './components/Convenience/ProductEditComponent.vue'; // 商品編集画面
import ConvenienceProductSaleIndexComponent from './components/Convenience/ProductSaleIndexComponent.vue'; // 出品した商品一覧画面
import ConvenienceProductPurchaseIndexComponent from './components/Convenience/ProductPurchaseIndexComponent.vue'; // 購入された商品一覧画面

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        // TOP画面
        {
            path: '/top',
            name: 'top',
            component: TopComponent
        },
        // 利用規約ページ
        {
            path: '/terms',
            name: 'terms',
            component: TermsComponent
        },
        // プライバシーポリシーページ
        {
            path: '/privacy',
            name: 'privacy',
            component: PrivacyPolicyComponent
        },
        // お問い合わせページ
        {
            path: '/contact',
            name: 'contact',
            component: ContactComponent
        },
        // エラーページ
        {
            path: '/500',
            name: 'error',
            component: ErrorComponent
        },
        // NotFoundページ
        {
            path: '/404',
            name: 'NotFound',
            component: NotFoundComponent
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
            component: UserLoginComponent,
        },
        // コンビニ側ログイン画面
        {
            path: '/convenience/login',
            name: 'convenience.login',
            component: ConvenienceLoginComponent,
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
        },
        // コンビニ側プロフィール編集画面
        {
            path: '/convenience/mypage/profile',
            name: 'convenience.profile',
            component: ConvenienceProfileComponent,
        },
        // 利用者側退会画面
        {
            path: '/user/mypage/withdraw',
            name: 'user.withdraw',
            component: UserWithdrawComponent,
        },
        // コンビニ側退会画面
        {
            path: '/convenience/mypage/withdraw',
            name: 'convenience.withdraw',
            component: ConvenienceWithdrawComponent,
        },
        // コンビニ側商品出品画面
        {
            path: '/convenience/products/create',
            name: 'convenience.products.create',
            component: ConvenienceProductSaleComponent,
        },
        // コンビニ側商品編集画面
        {
            path: '/convenience/products/edit/:productId',
            name: 'convenience.products.edit',
            component: ConvenienceProductEditComponent,
        },
        // コンビニ側出品した商品一覧画面
        {
            path: '/convenience/products/sale',
            name: 'convenience.products.sale',
            component: ConvenienceProductSaleIndexComponent,
        },
        // コンビニ側購入された商品一覧画面
        {
            path: '/convenience/products/purchase',
            name: 'convenience.products.purchase',
            component: ConvenienceProductPurchaseIndexComponent,
        },
        // コンビニ側商品一覧画面
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
        // コンビニ側商品詳細画面
        {
            path: '/products/detail/:productId',
            name: 'products.detail',
            component: ProductDetailComponent,
        },
        // 利用者側お気に入り登録した商品一覧画面
        {
            path: '/user/products/liked',
            name: 'user.products.liked',
            component: UserLikeProductComponent,
        },
        // 利用者側購入した商品一覧画面
        {
            path: '/user/products/purchased',
            name: 'user.products.purchased',
            component: UserPurchasedProductComponent,
        }
    ],
    // ページ遷移時に必ずページの最上部から表示させる
    scrollBehavior (to, from, savedPosition) {
        if (to.path === from.path && to.hash) {
            return { selector: to.hash };
        } else {
            return { x: 0, y: 0 };
        }
    }
});

// セッションが必要ないパス（TOP画面、利用規約、プライバシーポリシー、お問い合わせフォーム、ユーザー登録画面、ログイン画面、パスワードメール送信画面、パスワードリセット画面、商品一覧画面、商品詳細画面）
const publicPaths = [
    '/top',
    '/terms',
    '/privacy',
    '/contact',
    '/user/register',
    '/convenience/register',
    '/user/login',
    '/convenience/login',
    '/user/password/email',
    '/convenience/password/email',
    '/user/password/reset/:token',
    '/convenience/password/reset/:token',
    '/products',
    '/products/detail/:productId'
];

// セッションタイムアウトした場合のナビゲーションガード
router.beforeEach(async (to, from, next) => {
    const isLogin = store.getters['auth/check'];
    // セッションタイムアウトして、現在パスが公開パスではない場合はTOP画面にリダイレクト
    if (!isLogin && !publicPaths.some(path => to.path.startsWith(path)) && !to.path.includes('/password/reset')) { // パスワードリセット画面のパスは除外
        next('/top');
    } else {
        await store.dispatch('auth/currentUser'); // ログイン状態を保持
        next();
    }
});

export default router;