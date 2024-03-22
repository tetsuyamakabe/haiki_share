<div class="c-nav__menu--trigger js-toggle-sp-menu">
    <span></span>
    <span></span>
    <span></span>
</div>

<nav class="c-nav__menu js-toggle-sp-menu-target">
        <ul class="c-nav__menu--list">
            @if(request()->query('type') === 'user')
                <li class="c-nav__menu--item"><a class="c-nav__menu--link" href="{{ route('register', ['type' => 'user']) }}">{{ __('User Registration') }}</a></li>
                <li class="c-nav__menu--item"><a class="c-nav__menu--link" href="{{ route('login', ['type' => 'user']) }}">{{ __('User Login') }}</a></li>
            @else
                <li class="c-nav__menu--item"><a class="c-nav__menu--link" href="{{ route('register', ['type' => 'convenience']) }}">{{ __('Convenience Store Registration') }}</a></li>
                <li class="c-nav__menu--item"><a class="c-nav__menu--link" href="{{ route('login', ['type' => 'convenience']) }}">{{ __('Convenience Store Login') }}</a></li>
            @endif
        </ul>
</nav>