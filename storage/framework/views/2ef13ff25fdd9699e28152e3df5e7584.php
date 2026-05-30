

<?php $__env->startSection('title', 'Dashboard Admin'); ?>
<?php $__env->startSection('page-title', 'Dashboard Admin'); ?>

<?php $__env->startSection('content'); ?>
<?php $activeSection = $activeSection ?? 'dashboard'; ?>

<?php if($activeSection === 'profile'): ?>
<div class="container-fluid" style="max-width: 100%; overflow-x: hidden;">
    <?php echo $__env->make('dashboard.partials.profile-panel', [
        'title' => 'Profil Administrator',
        'description' => 'Profil admin sekarang ditampilkan sebagai panel internal agar tetap berada dalam shell dashboard admin.'
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php else: ?>
<div class="container-fluid" style="max-width: 100%; overflow-x: hidden;">
    <!-- Alert Pending Users -->
    <?php
        $pendingUsers = \App\Models\User::where('status', 'pending')->count();
    ?>
    <?php if($pendingUsers > 0): ?>
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Perhatian!</strong> Ada <strong><?php echo e($pendingUsers); ?></strong> pengguna baru yang menunggu persetujuan.
            <a href="<?php echo e(route('admin.users.pending')); ?>" class="alert-link">Lihat sekarang →</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Card Dashboard: 3 Atas, 3 Bawah, Responsive & Konsisten -->
    <div class="row g-2 g-md-4 mb-3 mb-md-4">
    <!-- Atas -->
    <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch">
        <div class="stat-card w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="border-inline-start: 4px solid #00B5A0; background: linear-gradient(135deg, #E6F6F2, #CFF2E9); min-height: 120px;">
            <div class="icon mb-2" style="background: #00B5A0; color: white; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem;">
                <i class="fas fa-users"></i>
            </div>
            <div style="font-size: 1rem; color: #00B5A0; font-weight: 700;">Total Pengguna</div>
            <div style="font-size: 1.3rem; color: #00B5A0; font-weight: 700;"><?php echo e($totalUsers); ?></div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch">
        <div class="stat-card w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="border-inline-start: 4px solid #1E88E5; background: linear-gradient(135deg, #E3F2FD, #BBDEFB); min-height: 120px;">
            <div class="icon mb-2" style="background: #1E88E5; color: white; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem;">
                <i class="fas fa-file-contract"></i>
            </div>
            <div style="font-size: 1rem; color: #1E88E5; font-weight: 700;">Total Perjanjian</div>
            <div style="font-size: 1.3rem; color: #1E88E5; font-weight: 700;"><?php echo e($totalPerjanjian); ?></div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch">
        <div class="stat-card w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="border-inline-start: 4px solid #FF9800; background: linear-gradient(135deg, #FFF3E0, #FFE0B2); min-height: 120px;">
            <div class="icon mb-2" style="background: #FF9800; color: white; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem;">
                <i class="fas fa-briefcase"></i>
            </div>
            <div style="font-size: 1rem; color: #FF9800; font-weight: 700;">Total Jabatan</div>
            <div style="font-size: 1.3rem; color: #FF9800; font-weight: 700;"><?php echo e($jabatanStats->count()); ?></div>
        </div>
    </div>
</div>

<div class="row g-2 g-md-4">
    <!-- Card: Total Program -->
    <div class="col-md-4">
        <div class="stat-card w-100 h-100 d-flex flex-column align-items-center justify-content-center" 
             style="border-inline-start: 4px solid #9C27B0; background: linear-gradient(135deg, #F3E5F5, #E1BEE7); min-height: 140px; cursor: pointer;"
             data-bs-toggle="modal" data-bs-target="#programModal">
            <div class="icon mb-2" style="background: #9C27B0; color: white; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.8rem;">
                <i class="fas fa-folder-open"></i>
            </div>
            <div style="font-size: 1rem; color: #9C27B0; font-weight: 700;">Total Program</div>
            <div style="font-size: 1.8rem; color: #9C27B0; font-weight: 700;"><?php echo e($totalPrograms); ?></div>
        </div>
    </div>

    <!-- Card: Total Kegiatan -->
    <div class="col-md-4">
        <div class="stat-card w-100 h-100 d-flex flex-column align-items-center justify-content-center" 
             style="border-inline-start: 4px solid #FF5722; background: linear-gradient(135deg, #FBE9E7, #FFCCBC); min-height: 140px; cursor: pointer;"
             data-bs-toggle="modal" data-bs-target="#kegiatanModal">
            <div class="icon mb-2" style="background: #FF5722; color: white; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.8rem;">
                <i class="fas fa-tasks"></i>
            </div>
            <div style="font-size: 1rem; color: #FF5722; font-weight: 700;">Total Kegiatan</div>
            <div style="font-size: 1.8rem; color: #FF5722; font-weight: 700;"><?php echo e($totalKegiatan); ?></div>
        </div>
    </div>

    <!-- Card: Total Sub-Kegiatan -->
    <div class="col-md-4">
        <div class="stat-card w-100 h-100 d-flex flex-column align-items-center justify-content-center" 
             style="border-inline-start: 4px solid #3F51B5; background: linear-gradient(135deg, #E8EAF6, #C5CAE9); min-height: 140px; cursor: pointer;"
             data-bs-toggle="modal" data-bs-target="#subKegiatanModal">
            <div class="icon mb-2" style="background: #3F51B5; color: white; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.8rem;">
                <i class="fas fa-list-ul"></i>
            </div>
            <div style="font-size: 1rem; color: #3F51B5; font-weight: 700;">Total Sub-Kegiatan</div>
            <div style="font-size: 1.8rem; color: #3F51B5; font-weight: 700;"><?php echo e($totalSubKegiatan); ?></div>
        </div>
    </div>
</div>

<div class="row g-2 g-md-4 mt-3">
        <div class="data-table">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="mb-0" style="font-size: 1rem;"><i class="fas fa-users"></i> Pengguna</h5>
                <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div style="max-height: 280px; overflow-y: auto; overflow-x: hidden;">
                <table class="table table-hover table-sm" style="margin-bottom: 0;">
                    <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                        <tr>
                            <th style="font-size: 0.9rem;">Nama</th>
                            <th style="font-size: 0.9rem;">Jabatan</th>
                            <th style="font-size: 0.9rem;">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td style="font-size: 0.9rem;"><strong><?php echo e(Str::limit($user->nama, 25)); ?></strong></td>
                                <td style="font-size: 0.9rem;"><?php echo e(Str::limit($user->jabatan ?? '-', 20)); ?></td>
                                <td style="font-size: 0.9rem;">
                                    <span class="badge bg-primary"><?php echo e($user->role); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted" style="font-size: 0.9rem;">Belum ada data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<!-- Modals for Program, Kegiatan, Sub-Kegiatan -->
<!-- Modal Program -->
<div class="modal fade" id="programModal" tabindex="-1" aria-labelledby="programModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: #9C27B0; color: white;">
                <h5 class="modal-title" id="programModalLabel">
                    <i class="fas fa-folder-open"></i> Daftar Program (<?php echo e($totalPrograms); ?>)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if(!empty($allPrograms)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 20%;">Kode</th>
                                    <th style="width: 50%;">Nama Program</th>
                                    <th style="width: 25%;">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $allPrograms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td><span class="badge bg-primary"><?php echo e($program['kode']); ?></span></td>
                                        <td><strong><?php echo e($program['nama']); ?></strong></td>
                                        <td><small class="text-muted"><?php echo e($program['deskripsi'] ?? '-'); ?></small></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                        <p>Belum ada data program</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Kegiatan -->
<div class="modal fade" id="kegiatanModal" tabindex="-1" aria-labelledby="kegiatanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: #FF5722; color: white;">
                <h5 class="modal-title" id="kegiatanModalLabel">
                    <i class="fas fa-tasks"></i> Daftar Kegiatan (<?php echo e($totalKegiatan); ?>)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if(!empty($allKegiatan)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 15%;">Kode</th>
                                    <th style="width: 35%;">Nama Kegiatan</th>
                                    <th style="width: 25%;">Program</th>
                                    <th style="width: 20%;">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $allKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td><span class="badge bg-warning"><?php echo e($kegiatan['kode']); ?></span></td>
                                        <td><strong><?php echo e($kegiatan['nama']); ?></strong></td>
                                        <td><small class="text-primary"><?php echo e($kegiatan['program']); ?></small></td>
                                        <td><small class="text-muted"><?php echo e($kegiatan['deskripsi'] ?? '-'); ?></small></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-tasks fa-3x mb-3"></i>
                        <p>Belum ada data kegiatan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sub-Kegiatan -->
<div class="modal fade" id="subKegiatanModal" tabindex="-1" aria-labelledby="subKegiatanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: #3F51B5; color: white;">
                <h5 class="modal-title" id="subKegiatanModalLabel">
                    <i class="fas fa-list-ul"></i> Daftar Sub-Kegiatan (<?php echo e($totalSubKegiatan); ?>)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if(!empty($allSubKegiatan)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 15%;">Kode</th>
                                    <th style="width: 30%;">Nama Sub-Kegiatan</th>
                                    <th style="width: 20%;">Kegiatan</th>
                                    <th style="width: 15%;">Program</th>
                                    <th style="width: 15%;">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $allSubKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $subKegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td><span class="badge bg-info"><?php echo e($subKegiatan['kode']); ?></span></td>
                                        <td><strong><?php echo e($subKegiatan['nama']); ?></strong></td>
                                        <td><small class="text-warning"><?php echo e($subKegiatan['kegiatan']); ?></small></td>
                                        <td><small class="text-primary"><?php echo e($subKegiatan['program']); ?></small></td>
                                        <td><small class="text-muted"><?php echo e($subKegiatan['deskripsi'] ?? '-'); ?></small></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-list-ul fa-3x mb-3"></i>
                        <p>Belum ada data sub-kegiatan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Jabatan & Perjanjian Tables -->
<div class="row g-4 mt-3">
    <!-- Jabatan Table -->
    <div class="col-md-6">
        <div class="data-table">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="mb-0"><i class="fas fa-briefcase"></i> Daftar Jabatan</h5>
                <a href="<?php echo e(route('admin.jabatan.index')); ?>" class="btn btn-sm btn-primary">Kelola</a>
            </div>
            <div style="max-height: 350px; overflow-y: auto; overflow-x: hidden;">
                <table class="table table-hover table-sm" style="margin-bottom: 0;">
                    <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                        <tr>
                            <th style="width: 40%;">Nama Jabatan</th>
                            <th style="width: 30%;">Tugas</th>
                            <th style="width: 20%;" class="text-center">User</th>
                            <th style="width: 10%;" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $jabatanStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jabatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($jabatan->nama_jabatan); ?></strong></td>
                                <td>
                                    <?php if($jabatan->tugas): ?>
                                        <?php echo e(Str::limit($jabatan->tugas, 50)); ?>

                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary"><?php echo e($jabatan->users_count ?? 0); ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if($jabatan->is_active): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">Belum ada jabatan</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Perjanjian -->
    <div class="col-md-6">
        <div class="data-table">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h5 class="mb-0"><i class="fas fa-file-contract"></i> Perjanjian Terbaru</h5>
                <div class="input-group input-group-sm" style="max-width: 320px;">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input id="adminPerjanjianSearch" type="text" class="form-control" placeholder="Cari nama, jabatan, status, atau tanggal" aria-label="Cari perjanjian">
                </div>
            </div>
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table id="adminPerjanjianTable" class="table table-hover table-sm">
                    <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                        <tr>
                            <th>Pembuat</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentPerjanjian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perjanjian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($perjanjian->user->nama ?? 'N/A'); ?></strong></td>
                                <td><?php echo e($perjanjian->user->jabatan ?? '-'); ?></td>
                                <td>
                                    <?php
                                        // Status logic sama dengan user dashboard
                                        if (!empty($perjanjian->rejected) && $perjanjian->rejected == true) {
                                            $status = 'rejected';
                                            $statusText = 'Ditolak';
                                            $badgeClass = 'bg-danger';
                                        } elseif (!empty($perjanjian->pihak2_signature)) {
                                            $status = 'approved';
                                            $statusText = 'Disetujui';
                                            $badgeClass = 'bg-success';
                                        } else {
                                            $status = 'waiting';
                                            $statusText = 'Menunggu';
                                            $badgeClass = 'bg-warning text-dark';
                                        }
                                    ?>
                                    <span class="badge <?php echo e($badgeClass); ?>"><?php echo e($statusText); ?></span>
                                </td>
                                <td><?php echo e($perjanjian->created_at->format('d/m/Y')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Live search pada tabel perjanjian
const adminPerjanjianSearch = document.getElementById('adminPerjanjianSearch');
const adminPerjanjianTableBody = document.querySelector('#adminPerjanjianTable tbody');

function filterAdminPerjanjianTable() {
    if (!adminPerjanjianTableBody) return;
    const term = (adminPerjanjianSearch?.value || '').toLowerCase();
    adminPerjanjianTableBody.querySelectorAll('tr').forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
}

if (adminPerjanjianSearch) {
    adminPerjanjianSearch.addEventListener('input', filterAdminPerjanjianTable);
    filterAdminPerjanjianTable();
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\admin\dashboard.blade.php ENDPATH**/ ?>