<div class="nav-container">
    <nav id="main-menu-navigation" class="navigation-main">
        <div class="nav-item {{ request()->routeIs(['employee.dashboard']) ? 'active' : '' }}">
            <a href="{{ route('employee.dashboard') }}"><i class="ik ik-bar-chart-2"></i><span>Dashboard</span></a>
        </div>

        <div class="nav-lavel">Manage Employees</div>

        <div class="nav-item">
            <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="ik log-out ik-log-out"></i><span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('employee.emp-logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

    </nav>
</div>
