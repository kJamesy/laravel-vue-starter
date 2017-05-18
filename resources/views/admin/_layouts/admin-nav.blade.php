<ul class="nav flex-column admin-nav bg-primary">
    <li class="nav-item">
        <a class="nav-link @yield('dashboard_active')" href="{{ route('admin.home') }}">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @yield('members_active')" href="{{ route('members.index') }}">Members</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @yield('users_active')" href="{{ route('users.index') }}">Users</a>
    </li>
</ul>