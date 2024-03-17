<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body>
        <header class="l-header js-float-menu">
            <div class="l-header__inner">
                <a class="c-header__title" href="{{ url('/') }}">
                    haiki share
                </a>
                @include('parts.nav_menu')
            </div>
        </header>
    </body>
</html>