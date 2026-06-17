<aside class="sidebar">
    <h3>Menu</h3>
    <div class="sidebar-menu">
        <a href="<?php echo e(route('direktur.perjanjian')); ?>" class="<?php echo e(request()->routeIs('direktur.perjanjian*') ? 'active' : ''); ?>">
            <i class="fas fa-file-contract"></i>
            Perjanjian Kinerja
        </a>
        <a href="<?php echo e(route('direktur.laporan')); ?>" class="<?php echo e(request()->routeIs('direktur.laporan*') ? 'active' : ''); ?>">
            <i class="fas fa-chart-line"></i>
            Laporan Kinerja
        </a>
        <a href="<?php echo e(route('dashboard.direktur')); ?>" class="<?php echo e(request()->routeIs('dashboard.direktur*') ? 'active' : ''); ?>">
            <i class="fas fa-check-circle"></i>
            Review & Persetujuan
        </a>
        <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'profil'])); ?>" class="<?php echo e(request()->routeIs('dashboard.direktur*') && request()->query('panel') === 'profil' ? 'active' : ''); ?>">
            <i class="fas fa-user"></i>
            Profil
        </a>
        <a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-right-from-bracket"></i>
            Keluar
        </a>
    </div>
</aside>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\dashboard\partials\pimpinan-sidebar.blade.php ENDPATH**/ ?>