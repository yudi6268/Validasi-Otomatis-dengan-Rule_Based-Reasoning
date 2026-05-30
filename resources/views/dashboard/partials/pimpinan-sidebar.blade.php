<aside class="sidebar">
    <h3>Menu</h3>
    <div class="sidebar-menu">
        <a href="{{ route('direktur.perjanjian') }}" class="{{ request()->routeIs('direktur.perjanjian*') ? 'active' : '' }}">
            <i class="fas fa-file-contract"></i>
            Perjanjian Kinerja
        </a>
        <a href="{{ route('direktur.laporan') }}" class="{{ request()->routeIs('direktur.laporan*') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i>
            Laporan Kinerja
        </a>
        <a href="{{ route('dashboard.direktur') }}" class="{{ request()->routeIs('dashboard.direktur*') ? 'active' : '' }}">
            <i class="fas fa-check-circle"></i>
            Review & Persetujuan
        </a>
        <a href="{{ route('dashboard.direktur', ['panel' => 'profil']) }}" class="{{ request()->routeIs('dashboard.direktur*') && request()->query('panel') === 'profil' ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            Profil
        </a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-right-from-bracket"></i>
            Keluar
        </a>
    </div>
</aside>
