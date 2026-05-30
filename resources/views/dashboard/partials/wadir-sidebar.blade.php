<aside class="sidebar">
    <h3>Menu</h3>
    <div class="sidebar-menu">
        <a href="{{ route('dashboard.wadir') }}" class="{{ request()->routeIs('dashboard.wadir') && !request()->query('panel') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i>
            Dashboard
        </a>
        <a href="{{ route('dashboard.wadir', ['panel' => 'perjanjian']) }}" class="{{ request()->routeIs('dashboard.wadir') && request()->query('panel') === 'perjanjian' ? 'active' : '' }}">
            <i class="fas fa-file-contract"></i>
            Perjanjian Kinerja
        </a>
        <a href="{{ route('dashboard.wadir', ['panel' => 'laporan']) }}" class="{{ request()->routeIs('dashboard.wadir') && request()->query('panel') === 'laporan' ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i>
            Laporan Kinerja
        </a>
        <a href="{{ route('laporan.kinerja', ['section' => 'validasi', 'from' => 'dashboard_wadir_laporan']) }}" class="{{ request()->routeIs('laporan.kinerja') && request()->get('section') === 'validasi' ? 'active' : '' }}">
            <i class="fas fa-check-double"></i>
            Validasi Laporan
        </a>
        <a href="{{ route('dashboard.wadir', ['panel' => 'profil']) }}" class="{{ request()->routeIs('dashboard.wadir') && request()->query('panel') === 'profil' ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            Profil
        </a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-right-from-bracket"></i>
            Keluar
        </a>
    </div>
</aside>
