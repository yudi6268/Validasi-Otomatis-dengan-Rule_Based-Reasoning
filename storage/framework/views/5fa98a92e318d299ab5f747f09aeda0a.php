

<?php $__env->startSection('title', 'Kelola Laporan Kinerja'); ?>
<?php $__env->startSection('page-title', 'Kelola Laporan Kinerja'); ?>

<?php $__env->startSection('content'); ?>


<div class="data-table mb-3">
    <form method="GET" action="<?php echo e(route('admin.laporan.index')); ?>" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label fw-600 small mb-1">Cari</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Nama, periode, tahun..." value="<?php echo e(request('search')); ?>">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-600 small mb-1">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="waiting"  <?php echo e(request('status') === 'waiting'  ? 'selected' : ''); ?>>Menunggu</option>
                <option value="approved" <?php echo e(request('status') === 'approved' ? 'selected' : ''); ?>>Disetujui</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-600 small mb-1">Tahun</label>
            <select name="tahun" class="form-select form-select-sm">
                <option value="">Semua</option>
                <?php $__currentLoopData = $tahunList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t); ?>" <?php echo e(request('tahun') == $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="<?php echo e(route('admin.laporan.index')); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>
</div>


<div class="d-flex align-items-center justify-content-between mb-2">
    <span class="text-muted small">
        Total: <strong><?php echo e($laporans->total()); ?></strong> laporan
    </span>
    <small class="text-muted">Halaman <?php echo e($laporans->currentPage()); ?> / <?php echo e($laporans->lastPage()); ?></small>
</div>


<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <strong>Triwulan Aktif:</strong>
        <span id="activeTriwulanBadge" class="badge bg-primary">TW <?php echo e($activeTriwulan ?? 1); ?></span>
        <small class="text-muted ms-2">(Tampilkan laporan untuk triwulan aktif sebagai referensi)</small>
    </div>

    <?php if(auth()->check() && method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin()): ?>
        <div>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#selectTriwulanModal">
                <i class="fas fa-calendar-alt"></i> Pilih Triwulan Laporan Aktif
            </button>
        </div>
    <?php else: ?>
        <a href="<?php echo e(route('admin.triwulan.setting')); ?>" class="btn btn-sm btn-outline-secondary">Lihat pengaturan triwulan</a>
    <?php endif; ?>
</div>


<?php if(auth()->check() && method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin()): ?>
<div class="modal fade" id="selectTriwulanModal" tabindex="-1" aria-labelledby="selectTriwulanLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectTriwulanLabel"><i class="fas fa-calendar-alt"></i> Pilih Triwulan Aktif</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="selectTriwulanForm">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Triwulan</label>
                        <select name="triwulan" id="selectTriwulanInput" class="form-select">
                            <?php for($i=1;$i<=4;$i++): ?>
                                <option value="<?php echo e($i); ?>" <?php echo e(($activeTriwulan ?? 1) == $i ? 'selected' : ''); ?>>TW <?php echo e($i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </form>
                <div id="selectTriwulanAlert" class="alert d-none" role="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="saveTriwulanBtn" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const saveBtn = document.getElementById('saveTriwulanBtn');
    if (!saveBtn) return;

    saveBtn.addEventListener('click', async function() {
        const form = document.getElementById('selectTriwulanForm');
        const select = document.getElementById('selectTriwulanInput');
        const alertBox = document.getElementById('selectTriwulanAlert');
        const badge = document.getElementById('activeTriwulanBadge');

        const triwulan = select.value;
        const token = form.querySelector('input[name="_token"]').value;

        alertBox.classList.add('d-none');

        try {
            const res = await fetch('<?php echo e(route('admin.triwulan.setting.update')); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ triwulan: parseInt(triwulan) })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                // Update badge and close modal
                badge.textContent = 'TW ' + data.triwulan;
                var modal = bootstrap.Modal.getInstance(document.getElementById('selectTriwulanModal'));
                modal.hide();

                // flash message (temporary)
                const flash = document.createElement('div');
                flash.className = 'alert alert-success mt-3';
                flash.textContent = data.message || 'Triwulan aktif berhasil diubah.';
                document.querySelector('.data-table').prepend(flash);
                setTimeout(() => flash.remove(), 3500);
            } else {
                alertBox.classList.remove('d-none');
                alertBox.classList.add('alert-danger');
                alertBox.textContent = data.message || 'Gagal mengubah triwulan.';
            }
        } catch (err) {
            alertBox.classList.remove('d-none');
            alertBox.classList.add('alert-danger');
            alertBox.textContent = 'Terjadi error saat menghubungi server.';
            console.error(err);
        }
    });
});
</script>
<?php $__env->stopPush(); ?>


<div class="data-table">
    <div class="table-responsive">
        <table class="table table-hover table-sm align-middle">
            <thead>
                <tr>
                    <th width="4%">#</th>
                    <th width="22%">Nama Pembuat</th>
                    <th width="18%">Jabatan</th>
                    <th width="10%">Periode</th>
                    <th width="8%">Tahun</th>
                    <th width="10%">Tanggal</th>
                    <th width="10%">Status</th>
                    <th width="18%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $laporans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        if (!empty($l->pihak2_signature)) {
                            $statusText = 'Disetujui';
                            $badgeClass = 'bg-success';
                        } else {
                            $statusText = 'Menunggu';
                            $badgeClass = 'bg-warning text-dark';
                        }
                        $periodeLabel = match((int)($l->periode ?? $l->triwulan_aktif)) {
                            1 => 'TW I',
                            2 => 'TW II',
                            3 => 'TW III',
                            4 => 'TW IV',
                            default => ($l->periode ?? $l->triwulan_aktif ?? '-')
                        };
                    ?>
                    <tr>
                        <td><?php echo e(($laporans->currentPage() - 1) * $laporans->perPage() + $idx + 1); ?></td>
                        <td>
                            <strong><?php echo e($l->pihak1_name ?? ($l->user->nama ?? 'N/A')); ?></strong><br>
                            <small class="text-muted"><?php echo e($l->pihak1_jabatan ?? ($l->user->jabatan ?? '-')); ?></small>
                        </td>
                        <td><small><?php echo e($l->jabatan ?? ($l->user->jabatan ?? '-')); ?></small></td>
                        <td>
                            <span class="badge bg-info text-dark"><?php echo e($periodeLabel); ?></span>
                        </td>
                        <td><?php echo e($l->tahun ?? '-'); ?></td>
                        <td><small><?php echo e($l->created_at ? $l->created_at->format('d/m/Y') : '-'); ?></small></td>
                        <td>
                            <span class="badge <?php echo e($badgeClass); ?>"><?php echo e($statusText); ?></span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#revisiModal<?php echo e($l->id); ?>"
                                    title="Revisi Status">
                                <i class="fas fa-edit"></i> Revisi
                            </button>
                            <button class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#hapusModal<?php echo e($l->id); ?>"
                                    title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    
                    <div class="modal fade" id="revisiModal<?php echo e($l->id); ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header" style="background:#00B5A0;color:#fff;">
                                    <h5 class="modal-title">
                                        <i class="fas fa-edit"></i> Revisi Status Laporan
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="<?php echo e(route('admin.laporan.revisi', $l->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="modal-body">
                                        <p class="mb-1"><strong>Pembuat:</strong> <?php echo e($l->pihak1_name ?? ($l->user->nama ?? 'N/A')); ?></p>
                                        <p class="mb-1"><strong>Periode:</strong> <?php echo e($periodeLabel); ?> — Tahun <?php echo e($l->tahun ?? '-'); ?></p>
                                        <p class="mb-3"><strong>Status saat ini:</strong> <span class="badge <?php echo e($badgeClass); ?>"><?php echo e($statusText); ?></span></p>

                                        <label class="form-label fw-bold">Ubah Status ke:</label>
                                        <div class="d-flex flex-column gap-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="action" value="reset" id="lreset<?php echo e($l->id); ?>" checked>
                                                <label class="form-check-label" for="lreset<?php echo e($l->id); ?>">
                                                    <span class="badge bg-warning text-dark">Menunggu</span> — Reset ke status awal (hapus tanda tangan)
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="action" value="approve" id="lapprove<?php echo e($l->id); ?>">
                                                <label class="form-check-label" for="lapprove<?php echo e($l->id); ?>">
                                                    <span class="badge bg-success">Disetujui</span> — Setujui paksa oleh admin
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    
                    <div class="modal fade" id="hapusModal<?php echo e($l->id); ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title"><i class="fas fa-trash"></i> Hapus Laporan</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Yakin menghapus laporan <strong><?php echo e($periodeLabel); ?></strong> milik <strong><?php echo e($l->pihak1_name ?? ($l->user->nama ?? 'N/A')); ?></strong>? Tindakan ini tidak dapat dibatalkan.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="<?php echo e(route('admin.laporan.destroy', $l->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Ya, Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="fas fa-chart-line fa-3x mb-3 d-block text-muted"></i>
                            Belum ada data laporan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <div class="d-flex justify-content-end mt-3">
        <?php echo e($laporans->links()); ?>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views/admin/laporan/index.blade.php ENDPATH**/ ?>