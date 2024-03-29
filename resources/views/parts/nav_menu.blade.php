<div class="c-nav__menu--trigger js-toggle-sp-menu">
    <span></span>
    <span></span>
    <span></span>
</div>

<nav class="c-nav__menu js-toggle-sp-menu-target">
        <ul class="c-nav__menu--list">
            @if(request()->route()->getName() === 'user.register.show')
                <li class="c-nav__menu--item"><a class="c-nav__menu--link" href="{{ route('user.register.show') }}">{{ __('User Register') }}</a></li>
                <li class="c-nav__menu--item"><a class="c-nav__menu--link" href="{{ route('user.login.show') }}">{{ __('User Login') }}</a></li>
            @else
                <li class="c-nav__menu--item"><a class="c-nav__menu--link" href="{{ route('convenience.register.show') }}">{{ __('Convenience Store Register') }}</a></li>
                <li class="c-nav__menu--item"><a class="c-nav__menu--link" href="{{ route('convenience.login.show') }}">{{ __('Convenience Store Login') }}</a></li>
            @endif
        </ul>
</nav>