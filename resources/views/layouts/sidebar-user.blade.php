<ul class="navbar-nav bg-white sidebar accordion shadow" id="accordionSidebar" style="min-height: 100vh; width: 250px; color: #000;">
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-3" href="{{ url('/dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('assets/images/AVI.png') }}" alt="AVI LOGO" class="img-fluid" style="max-height: 40px; width: auto;">
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center text-black" href="{{ url('/dashboard') }}">
            <i class="fas fa-home me-2 text-black"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item {{ request()->is('inputdata') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center text-black" href="{{ route('inputdata.create') }}">
            <i class="fas fa-edit me-2 text-black"></i>
            <span>Input Data</span>
        </a>
    </li>

    {{-- Logout --}}
    <li class="nav-item">
        <a class="nav-link d-flex align-items-center text-black" href="#" onclick="confirmLogout(event)">
            <i class="fas fa-sign-out-alt me-2 text-black"></i>
            <span>Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </li>
</ul>

@push('scripts')
<script>
    function confirmLogout(event) {
        event.preventDefault();
        if (confirm("Yakin ingin logout?")) {
            document.getElementById('logout-form').submit();
        }
    }
</script>
@endpush
