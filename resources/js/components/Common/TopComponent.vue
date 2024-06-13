<template>
    <main class="l-container">
        <section class="c-hero">
            <div class="c-hero__inner">
                <h2 class="c-hero__title">haiki share</h2>
                <p class="c-hero__text u-fz-20@sm">食品ロス問題を解決するWebサービス</p>
            </div>
        </section>

        <section class="l-container__inner u-bgColor" id="about">
            <h2 class="c-title c-title--top">サービス概要</h2>
            <div class="c-content">
                <img :src="'/images/attachment.jpeg'" alt="てまえどり画像" class="c-attachment">
                <p class="c-content__text">
                    「haiki share」は、食品ロス問題を解決するために作られたWebサービスです。<br>
                    農林水産省によると、令和2年度の食品ロスの推計値は年間で522万トン発生しており、そのうち60万トンはコンビニを含む食品小売業から出ています。<br>
                    コンビニ大手チェーン店では、食品ロスと廃棄物への取り組みとして、「てまえどり」を呼びかけています。「てまえどり」とは、商品棚の手前に陳列された食品を積極的に選ぶ行動のことです。これにより、期限が過ぎて廃棄されることによる食品ロスを削減する効果が期待されます。<br>
                    しかし、消費者の理解がまだ十分でないために、食品ロスはまだ課題が残っています。<br>
                    そこで、食品ロス問題が少しでも解決できるように、より多くの方々に理解し、行動するきっかけになるために「haiki share」があります。<br>
                </p>
            </div>
        </section>

        <section class="l-container__inner" id="merit">
            <h2 class="c-title c-title--top">メリット</h2>
            <div class="c-content">
                <div class="c-panel__group c-panel__group--flex">
                    <div class="c-panel c-panel__border c-panel--user">
                        <div class="c-panel__head">
                            <span class="u-ftColor__user">利用者側（廃棄を買う側）</span>
                        </div>
                        <div class="c-panel__body">
                            <img :src="'/images/merit_user.png'" alt="利用者メリット画像" class="c-panel__image c-panel__image--user">
                        </div>
                        <div class="c-panel__foot">
                            <p class="c-content__text">食品を通常価格より安く手に入れることができます。</p>
                        </div>
                    </div>
                    <div class="c-panel c-panel__border c-panel--convenience">
                        <div class="c-panel__head">
                            <span class="u-ftColor__convenience">コンビニ側</span>
                        </div>
                        <div class="c-panel__body">
                            <img :src="'/images/merit_convenience.png'" alt="コンビニメリット画像" class="c-panel__image c-panel__image--convenience">
                        </div>
                        <div class="c-panel__foot">
                            <p class="c-content__text">賞味期限が近い食品を廃棄せず、売り上げを増やすことができます。</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="l-container__inner">
            <RouterLink class="c-button c-button--top c-button--primary u-fz-20@sm" to="/products">出品されている商品を見てみる</RouterLink>
        </section>

        <section class="l-container__inner" id="usage">
            <h2 class="c-title c-title--top">利用方法</h2>
            <div class="c-content">
                <div class="c-panel__group c-panel__group--flex">
                    <div class="c-panel c-panel__border c-panel__user">
                        <div class="c-panel__head">
                            <span class="u-ftColor__user">利用者側（廃棄を買う側）</span>
                        </div>
                        <div class="c-panel__container">
                            <div class="c-panel__content">
                                <img :src="'/images/register_image.png'" alt="利用方法画像" class="c-panel__image c-panel__image--usage">
                                <p class="c-content__text">１．ユーザー登録する</p>
                            </div>
                            <div class="c-panel__content">
                                <img :src="'/images/user_purchase.png'" alt="利用方法画像" class="c-panel__image c-panel__image--usage">
                                <p class="c-content__text">２．商品一覧から気になる商品を探す</p>
                            </div>
                            <div class="c-panel__content">
                                <img :src="'/images/purchase.png'" alt="利用方法画像" class="c-panel__image c-panel__image--usage">
                                <p class="c-content__text">３．購入の意思が固まったら、商品詳細画面で購入ボタンを押す</p>
                            </div>
                            <div class="c-panel__content">
                                <img :src="'/images/convenience.png'" alt="利用方法画像" class="c-panel__image c-panel__image--usage">
                                <p class="c-content__text">４．利用者はコンビニに商品を購入しに行く</p>
                            </div>
                        </div>
                        <div class="c-panel__foot">
                            <RouterLink class="c-button c-button--main c-button--register" to="/user/register">利用者ユーザー登録</RouterLink>
                        </div>
                    </div>
                    <div class="c-panel c-panel__border c-panel__convenience">
                        <div class="c-panel__head">
                            <span class="u-ftColor__convenience">コンビニ側</span>
                        </div>
                        <div class="c-panel__container">
                            <div class="c-panel__content">
                                <img :src="'/images/register_image.png'" alt="利用方法画像" class="c-panel__image c-panel__image--usage">
                                <p class="c-content__text">１．ユーザー登録する</p>
                            </div>
                            <div class="c-panel__content">
                                <img :src="'/images/product.png'" alt="利用方法画像" class="c-panel__image c-panel__image--usage">
                                <p class="c-content__text">２．廃棄となった商品を登録する</p>
                            </div>
                            <div class="c-panel__content">
                                <img :src="'/images/convenience_man.png'" alt="利用方法画像" class="c-panel__image c-panel__image--usage">
                                <p class="c-content__text">３．「商品購入完了メール」というメール通知があれば、実際の商品を用意しておく</p>
                            </div>
                        </div>
                        <div class="c-panel__foot">
                            <RouterLink class="c-button c-button--main c-button--register" to="/convenience/register">コンビニユーザー登録</RouterLink>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="l-container__inner" id="contact">
            <h2 class="c-title c-title--top">お問い合わせ</h2>
            <!-- フラッシュメッセージを表示 -->
            <Toast />
            <div class="c-container">
                <form @submit.prevent="submitForm" class="c-form">
                    <!-- お名前 -->
                    <label for="name" class="c-label">お名前<span class="c-badge">必須</span></label>
                    <span v-if="errors && errors.name" class="c-error">{{ errors.name[0] }}</span>
                    <input v-model="formData.name" id="name" type="name" class="c-input" :class="{ 'is-invalid': errors && errors.name }" autocomplete="name">
                    <!-- メールアドレス -->
                    <label for="email" class="c-label">メールアドレス<span class="c-badge">必須</span></label>
                    <span v-if="errors && errors.email" class="c-error">{{ errors.email[0] }}</span>
                    <input v-model="formData.email" id="email" type="email" class="c-input" :class="{ 'is-invalid': errors && errors.email }" autocomplete="email">
                    <!--お問い合わせ内容 -->
                    <label for="contact" class="c-label">お問い合わせ内容<span class="c-badge">必須</span></label>
                    <span v-if="errors && errors.contact" class="c-error">{{ errors.contact[0] }}</span>
                    <textarea v-model="formData.contact" maxlength="255" id="contact" type="text" class="c-textarea" cols="30" rows="10" @keyup="countCharacters" :class="{ 'is-invalid': errors && errors.contact }"></textarea>
                    <span class="c-textarea--count">{{ formData.contact.length }} / 255文字</span>
                    <!-- 送信ボタン -->
                    <button type="submit" class="c-button c-button--submit c-button--main">送信する</button>
                </form>
            </div>
        </section>
    </main>
</template>

<script>
import Toast from '../Parts/Toast.vue'; // Toastコンポーネントをインポート

export default {
    components: {
        Toast, // Toastコンポーネントを読み込み
    },

    data() {
        return {
            formData: {
                name: '', // お名前
                email: '', // メールアドレス
                contact: '', // お問い合わせ内容
            },
            textareaCount: 0, // お問い合わせ内容の文字数カウント初期値
            errors: null, // エラーメッセージ
        };
    },

    methods: {
        // 入力された値をサーバー側に送信するメソッド
        submitForm() {
            axios.post('/api/contact', this.formData).then(response => {
                this.$store.dispatch('flash/setFlashMessage', { // フラッシュメッセージの表示
                    message: 'お問い合わせ内容を受け付けました。',
                    type: 'success'
                });
            }).catch(error => {
                this.errors = error.response.data.errors;
            });
        },

        // お問い合わせ内容の文字数をカウントするメソッド
        countCharacters() {
            this.countCharactersLength = this.formData.contact.length; // 文字数のカウント
            if (this.countCharactersLength > 255) { // 文字数が50文字を超えているか
                this.formData.count = this.formData.count.slice(0, 255); // 文字列を255文字まで切り抜く
                this.countCharactersLength = 255; // 255文字以上の文字数を制限する
            }
        },
    }
}
</script>