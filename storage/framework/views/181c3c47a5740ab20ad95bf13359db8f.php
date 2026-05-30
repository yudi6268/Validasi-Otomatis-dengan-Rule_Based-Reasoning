<?php if(isset($jabatan['status']) && isset($jabatan['body'])): ?>
    <div class="alert alert-danger">
        <b>Supabase Error (Status <?php echo e($jabatan['status']); ?>):</b><br>
        <pre style="white-space:pre-wrap;word-break:break-all;"><?php echo e($jabatan['body']); ?></pre>
    </div>
<?php elseif(empty($jabatan) || (is_array($jabatan) && count($jabatan) === 0)): ?>
    <div class="alert alert-warning text-center">Data tidak ditemukan.</div>
<?php else: ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Jabatan</th>
                <th>Tugas</th>
                <th>Fungsi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $jabatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($i + 1); ?></td>
                <td><?php echo e($item['nama_jabatan']); ?></td>
                <td><?php echo e(\Illuminate\Support\Str::limit($item['tugas'] ?? '-', 60)); ?></td>
                <td>
                    <?php
                        $fungsi = $item['fungsi'];
                        if (is_string($fungsi)) {
                            $fungsi = json_decode($fungsi, true);
                        }
                    ?>
                    <?php if(!empty($fungsi) && is_array($fungsi)): ?>
                        <ul class="mb-0">
                            <?php $__currentLoopData = $fungsi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($f); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo e($item['is_active'] ? 'Aktif' : 'Nonaktif'); ?>

                </td>
                <td>
                    <a href="<?php echo e(route('admin.jabatan.edit', $item['id'])); ?>" class="btn btn-warning btn-sm">Edit</a>
                    <form action="<?php echo e(route('admin.jabatan.destroy', $item['id'])); ?>"
                          method="POST" style="display:inline">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Hapus data ini?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php endif; ?>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\admin\jabatan\partials\table.blade.php ENDPATH**/ ?>