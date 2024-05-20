// フロートヘッダーメニュー
export default function floatHeader() {
    var targetHeight = $('.js-float-menu-target').height();
    $(window).on('scroll', function(){
        $('.js-float-menu').toggleClass('float-active', $(this).scrollTop() > targetHeight );
    });
}