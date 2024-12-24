<nav>
    <navbar-component :rol="{{ Auth::user()->getRoleNames() }}"></navbar-component>
</nav>
