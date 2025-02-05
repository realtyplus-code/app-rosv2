<nav>
    <navbar-component  :permissions="{{ Auth::user()->getAllPermissions() }}"></navbar-component>
    <drawer-component></drawer-component>
</nav>
