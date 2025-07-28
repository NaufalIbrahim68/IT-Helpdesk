<ul class="navbar-nav bg-white sidebar accordion shadow" id="accordionSidebar" style="min-height: 100vh; width: 250px; color: #000;">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-3" href="{{ url('/dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('assets/images/AVI.png') }}" alt="AVI LOGO" class="img-fluid" style="max-height: 40px; width: auto;">
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center text-black" href="{{ url('admin/dashboard') }}">
            <i class="fas fa-th me-2 text-black"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- TICKETING -->
    <div class="sidebar-heading px-3 text-black">TICKETING</div>

    <li class="nav-item {{ request()->is('ticketing*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center text-black" href="{{ route('ticketing.index') }}">
            <i class="fas fa-ticket-alt me-2 text-black"></i>
            <span>Ticketing</span>
        </a>
    </li>
<hr class="sidebar-divider">
    
    <div class="sidebar-heading px-3 text-black">Users</div>


    <li class="nav-item {{ request()->is('priorities*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center text-black" href="{{ route('prioritas.index') }}">
            <i class="fas fa-flag me-2 text-black"></i>
            <span>Priorities</span>
        </a>
    </li>

   

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link d-flex align-items-center text-black" href="#" onclick="confirmLogout(event)">
            <i class="fas fa-sign-out-alt me-2 text-black"></i>
            <span>Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </li>

    <hr class="sidebar-divider">

    <div class="text-center d-none d-md-inline my-3">
        <button class="rounded-circle border-0 shadow" id="sidebarToggle"></button>
    </div>

</ul>

@push('scripts')
<script>
    function confirmLogout(event) {
        event.preventDefault();
        if (confirm("Apakah Anda yakin ingin logout?")) {
            document.getElementById('logout-form').submit();
        }
    }
</script>
@endpush
