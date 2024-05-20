<template>
    <main class="l-container">
            <section class="c-hero js-float-menu-target">
                <h2 class="c-hero__title">haiki share</h2>
            </section>

            <section class="u-color__bg u-pd__xl u-pd__xl" id="about">
                <h2 class="c-title u-mb__xl">サービス概要</h2>
                <div class="c-container">
                    <div class="c-container__body">
                        <img :src="'/images/attachment.jpeg'" alt="てまえどり画像" class="c-attachment">
                        <p>
                            「haiki share」は、食品ロス問題を解決するために作られたWebサービスです。<br>
                            農林水産省によると、令和2年度の食品ロスの推計値は年間で522万トン発生しており、そのうち60万トンはコンビニを含む食品小売業から出ています。<br>
                            コンビニ大手チェーン店では、食品ロスと廃棄物への取り組みとして、「てまえどり」を呼びかけています。「てまえどり」とは、商品棚の手前に陳列された食品を積極的に選ぶ行動のことです。これにより、期限が過ぎて廃棄されることによる食品ロスを削減する効果が期待されます。<br>
                            しかし、消費者の理解がまだ十分でないために、食品ロスはまだ課題が残っています。<br>
                            そこで、食品ロス問題が少しでも解決できるように、より多くの方々に理解し、行動するきっかけになるために「haiki share」があります。<br>
                        </p>
                    </div>
                </div>
            </section>

            <section class="" id="merit">
                <h2 class="c-title u-mt__xl u-mb__xl">メリット</h2>
                <div class="c-container">
                    <div class="c-container__body">
                        <div class="c-panel__group c-panel__group--flex">
                            <div class="c-panel c-panel__border c-panel__user">
                                <div class="c-panel__head">
                                    <span class="u-color__user">利用者側（廃棄を買う側）</span>
                                </div>
                                <div class="c-panel__body">
                                    <img :src="'/images/merit_user.png'" alt="利用者メリット画像" class="c-panel__image c-panel__image--user">
                                </div>
                                <div class="c-panel__foot">
                                    <p>食品を通常価格より安く手に入れることができます。</p>
                                </div>
                            </div>

                            <div class="c-panel c-panel__border c-panel__convenience">
                                <div class="c-panel__head">
                                    <span class="u-color__convenience">コンビニ側</span>
                                </div>
                                <div class="c-panel__body">
                                    <img :src="'/images/merit_convenience.png'" alt="コンビニメリット画像" class="c-panel__image c-panel__image--convenience">
                                </div>
                                <div class="c-panel__foot">
                                    <p>賞味期限が近い食品を廃棄せず、売り上げを増やすことができます。</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="" id="usage">
                <h2 class="c-title u-mt__xl u-mb__xl">利用方法</h2>
                <div class="c-container">
                    <div class="c-container__body">
                        <div class="c-panel__group c-panel__group--flex">
                            <div class="c-panel c-panel__border c-panel__user">
                                <div class="c-panel__head">
                                    <span class="u-color__user">利用者側（廃棄を買う側）</span>
                                </div>
                                <div class="c-panel__container">
                                    <div class="c-panel__content">
                                        <img :src="'/images/register_image.png'" alt="利用方法画像" class="c-panel__image c-panel__image--usage">
                                        <p>１．ユーザー登録する</p>
                                    </div>
                                    <div class="c-panel__content">
                                        <img :src="'/images/user_purchase.png'" alt="利用方法画像" class="c-panel__image c-panel__image--usage">
                                        <p>２．商品一覧から気になる商品を探す</p>
                                    </div>
                                    <div class="c-panel__content">
                                        <img :src="'/images/purchase.png'" alt="利用方法画像" class="c-panel__image c-panel__image--usage">
                                        <p>３．購入の意思が固まったら、商品詳細画面で購入ボタンを押す</p>
                                    </div>
                                    <div class="c-panel__content">
                                        <img :src="'/images/convenience.png'" alt="利用方法画像" class="c-panel__image c-panel__image--usage">
                                        <p>４．利用者はコンビニに商品を購入しに行く</p>
                                    </div>
                                </div>
                                <div class="c-panel__foot">
                                    <RouterLink class="c-button c-button__register u-pd__s" to="/user/register">利用者ユーザー登録</RouterLink>
                                </div>
                            </div>

                            <div class="c-panel c-panel__border c-panel__convenience">
                                <div class="c-panel__head">
                                    <span class="u-color__convenience">コンビニ側</span>
                                </div>
                                <div class="c-panel__container">
                                    <div class="c-panel__content">
                                        <img :src="'/images/register_image.png'" alt="利用方法画像" class="c-panel__image c-panel__image--usage">
                                        <p>１．ユーザー登録する</p>
                                    </div>
                                    <div class="c-panel__content">
                                        <img :src="'/images/product.png'" alt="利用方法画像" class="c-panel__image c-panel__image--usage">
                                        <p>２．廃棄となった商品を登録する</p>
                                    </div>
                                    <div class="c-panel__content">
                                        <img :src="'/images/convenience_man.png'" alt="利用方法画像" class="c-panel__image c-panel__image--usage">
                                        <p>３．「商品購入完了メール」というメール通知があれば、実際の商品を用意しておく</p>
                                    </div>
                                </div>
                                <div class="c-panel__foot">
                                    <RouterLink class="c-button c-button__register c-button__register--convenience u-pd__s" to="/convenience/register">コンビニユーザー登録</RouterLink>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="u-pd__xl u-pd__xl" id="contact">
                <h2 class="c-title u-mt__xl u-mb__xl">お問い合わせ</h2>
                <div class="c-container">
                    <div class="c-container__body">
                        <form action="" class="c-form">
                            <input type="text" class="c-input u-pd__s u-mt__m u-mb__m" placeholder="お名前">
                            <input type="email" class="c-input u-pd__s u-mt__m u-mb__m" placeholder="email">
                            <textarea name="" id="" class="c-textarea u-pd__s u-mt__m u-mb__m" placeholder="お問い合わせ内容" cols="30" rows="10"></textarea>
                            <button type="submit" class="c-button c-button__contact u-pd__s">送信</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
</template>

<script>
    export default {}
</script>