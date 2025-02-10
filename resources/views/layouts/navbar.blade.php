<nav>
    <navbar-component :user-auth="{{ json_encode(Auth::user()) }}" :permissions="{{ Auth::user()->getAllPermissions() }}"></navbar-component>
    <drawer-component></drawer-component>
</nav>
