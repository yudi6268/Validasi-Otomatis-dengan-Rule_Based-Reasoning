<div class="data-table">
    <h5 class="mb-4"><i class="fas fa-users"></i> Daftar Pengguna (<?php echo e($users->total()); ?>)</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID Pegawai</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>NIP</th>
                    <th>Jabatan</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($loop->iteration + ($users->currentPage() - 1) * $users->perPage()); ?></td>
                        <td><strong><?php echo e($user->id_pegawai); ?></strong></td>
                        <td><?php echo e($user->nama); ?></td>
                        <td><?php echo e($user->email); ?></td>
                        <td><?php echo e($user->nip); ?></td>
                        <td><?php echo e($user->jabatan); ?></td>
                        <td>
                            <?php
                                $badgeClass = match($user->role) {
                                    'admin' => 'bg-danger',
                                    'direktur' => 'bg-primary',
                                    'wadir' => 'bg-info',
                                    'kabag-kabid' => 'bg-warning',
                                    'katimker-staf' => 'bg-success',
                                    default => 'bg-secondary'
                                };
                            ?>
                            <span class="badge <?php echo e($badgeClass); ?>"><?php echo e(ucfirst($user->role)); ?></span>
                        </td>
                        <td>
                            <?php
                                $statusClass = match($user->status) {
                                    'active' => 'bg-success',
                                    'pending' => 'bg-warning text-dark',
                                    'non-active' => 'bg-secondary',
                                    default => 'bg-light text-dark'
                                };
                            ?>
                            <span class="badge <?php echo e($statusClass); ?>"><?php echo e($user->status === 'active' ? 'Aktif' : ($user->status === 'pending' ? 'Pending' : 'Non Aktif')); ?></span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-sm btn-warning" title="Edit user"><i class="fas fa-edit"></i></a>
                            <form action="<?php echo e(route('admin.users.reset-password', $user)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-info" title="Reset password (generate random password)" onclick="return confirm('Reset password untuk <?php echo e($user->nama); ?>? Password baru akan di-generate otomatis.')"><i class="fas fa-key"></i></button>
                            </form>
                            <?php if($user->role !== 'admin'): ?>
                                <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pengguna ini?')"><i class="fas fa-trash"></i></button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">Tidak ada pengguna ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="mt-3">
            <?php echo e($users->links()); ?>

        </div>
    </div>
</div>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\admin\users\partials\table.blade.php ENDPATH**/ ?>