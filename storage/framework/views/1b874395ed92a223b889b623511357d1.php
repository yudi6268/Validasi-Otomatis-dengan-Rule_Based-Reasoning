

<?php $__env->startSection('title', 'Kirim Notifikasi Deadline'); ?>
<?php $__env->startSection('page-title', 'Kirim Notifikasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="data-table" style="max-width: 720px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1"><i class="fas fa-bell"></i> Notifikasi</h5>
            <small class="text-muted">Notifikasi ini akan muncul di sudut kiri atas dashboard pengguna sebagai pengingat batas waktu.</small>
        </div>
        <a href="<?php echo e(route('admin.notifications.index')); ?>" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger mb-3">
            <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.notifications.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        
        <div class="mb-4">
            <label class="form-label fw-bold">Jenis Notifikasi <span class="text-danger">*</span></label>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="d-flex align-items-center gap-3 p-3 border rounded-3 cursor-pointer <?php echo e(old('jenis') === 'laporan' ? 'border-warning bg-warning bg-opacity-10' : 'border-secondary-subtle'); ?>" style="cursor:pointer;" id="cardLaporan">
                        <input class="form-check-input mt-0 flex-shrink-0" type="radio" name="jenis" value="laporan"
                               id="jenisLaporan" <?php echo e(old('jenis', 'laporan') === 'laporan' ? 'checked' : ''); ?>

                               onchange="onJenisChange()">
                        <div>
                            <div class="fw-bold"><i class="fas fa-chart-line text-warning"></i> Batas Laporan Kinerja</div>
                            <small class="text-muted">Ingatkan pengguna untuk membuat laporan kinerja triwulan.</small>
                        </div>
                    </label>
                </div>
                <div class="col-md-6">
                    <label class="d-flex align-items-center gap-3 p-3 border rounded-3 cursor-pointer <?php echo e(old('jenis') === 'perjanjian' ? 'border-primary bg-primary bg-opacity-10' : 'border-secondary-subtle'); ?>" style="cursor:pointer;" id="cardPerjanjian">
                        <input class="form-check-input mt-0 flex-shrink-0" type="radio" name="jenis" value="perjanjian"
                               id="jenisPerjanjian" <?php echo e(old('jenis') === 'perjanjian' ? 'checked' : ''); ?>

                               onchange="onJenisChange()">
                        <div>
                            <div class="fw-bold"><i class="fas fa-file-contract text-primary"></i> Batas Perjanjian Kinerja</div>
                            <small class="text-muted">Ingatkan pengguna untuk membuat perjanjian kinerja tahunan.</small>
                        </div>
                    </label>
                </div>
            </div>
            <?php $__errorArgs = ['jenis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Tahun <span class="text-danger">*</span></label>
                <select name="tahun" class="form-select">
                    <?php for($y = date('Y') - 1; $y <= date('Y') + 2; $y++): ?>
                        <option value="<?php echo e($y); ?>" <?php echo e(old('tahun', date('Y')) == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                    <?php endfor; ?>
                </select>
                <?php $__errorArgs = ['tahun'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="col-md-4" id="triwulanWrapper">
                <label class="form-label fw-bold">Triwulan <span class="text-danger">*</span></label>
                <select name="triwulan" class="form-select" id="triwulanSelect">
                    <option value="1" <?php echo e(old('triwulan', '1') == '1' ? 'selected' : ''); ?>>Triwulan I (Jan – Mar)</option>
                    <option value="2" <?php echo e(old('triwulan') == '2' ? 'selected' : ''); ?>>Triwulan II (Apr – Jun)</option>
                    <option value="3" <?php echo e(old('triwulan') == '3' ? 'selected' : ''); ?>>Triwulan III (Jul – Sep)</option>
                    <option value="4" <?php echo e(old('triwulan') == '4' ? 'selected' : ''); ?>>Triwulan IV (Okt – Des)</option>
                </select>
                <?php $__errorArgs = ['triwulan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="col-md-4">
                <label class="form-label fw-bold">Tanggal Batas Akhir <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_batas" class="form-control"
                       value="<?php echo e(old('tanggal_batas')); ?>" min="<?php echo e(date('Y-m-d')); ?>">
                <?php $__errorArgs = ['tanggal_batas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        
        <div class="mb-3">
            <label class="form-label fw-bold">Tingkat Urgensi <span class="text-danger">*</span></label>
            <div class="d-flex gap-3 flex-wrap">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" value="info" id="typeInfo"
                           <?php echo e(old('type', 'warning') === 'info' ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="typeInfo">
                        <span class="badge bg-info">ℹ️ Info</span> — Pemberitahuan biasa
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" value="warning" id="typeWarning"
                           <?php echo e(old('type', 'warning') === 'warning' ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="typeWarning">
                        <span class="badge bg-warning text-dark">⚠️ Peringatan</span> — Mendekati batas waktu
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" value="danger" id="typeDanger"
                           <?php echo e(old('type') === 'danger' ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="typeDanger">
                        <span class="badge bg-danger">🚨 Mendesak</span> — Segera sebelum batas waktu
                    </label>
                </div>
            </div>
            <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div class="mb-3">
            <label class="form-label fw-bold">Pesan Tambahan <small class="text-muted fw-normal">(opsional)</small></label>
            <textarea name="pesan_tambahan" class="form-control" rows="3"
                      placeholder="Contoh: Harap segera hubungi bagian kepegawaian jika ada kesulitan..."><?php echo e(old('pesan_tambahan')); ?></textarea>
        </div>

        
        <div class="mb-4">
            <label class="form-label fw-bold">Penerima <span class="text-danger">*</span></label>
            <div class="d-flex gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="recipient_type" value="all"
                           id="recipientAll" <?php echo e(old('recipient_type', 'all') === 'all' ? 'checked' : ''); ?>

                           onchange="toggleUserSelect(false)">
                    <label class="form-check-label" for="recipientAll">
                        <i class="fas fa-users text-success"></i> Semua Pengguna
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="recipient_type" value="specific"
                           id="recipientSpecific" <?php echo e(old('recipient_type') === 'specific' ? 'checked' : ''); ?>

                           onchange="toggleUserSelect(true)">
                    <label class="form-check-label" for="recipientSpecific">
                        <i class="fas fa-user text-primary"></i> Pengguna Tertentu
                    </label>
                </div>
            </div>
            <div class="mt-2" id="userSelectWrapper" style="display:<?php echo e(old('recipient_type') === 'specific' ? 'block' : 'none'); ?>;">
                <select name="user_ids[]" class="form-select" id="userSelect" multiple size="6">
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>" <?php echo e(in_array($user->id, old('user_ids', [])) ? 'selected' : ''); ?>>
                            <?php echo e($user->nama); ?> — <?php echo e($user->jabatan ?? 'Tanpa jabatan'); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <div class="form-text">Tahan Ctrl/Cmd atau Shift untuk memilih lebih dari satu pengguna.</div>
            </div>
            <?php $__errorArgs = ['recipient_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php $__errorArgs = ['user_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php $__errorArgs = ['user_ids.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div class="alert alert-secondary mb-4" id="previewBox">
            <div class="d-flex align-items-start gap-2">
                <i class="fas fa-bell mt-1 text-warning"></i>
                <div>
                    <div class="fw-bold" id="previewTitle">—</div>
                    <div class="small text-muted" id="previewMsg">—</div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Kirim Notifikasi
            </button>
            <a href="<?php echo e(route('admin.notifications.index')); ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleUserSelect(show) {
    document.getElementById('userSelectWrapper').style.display = show ? 'block' : 'none';
}

function onJenisChange() {
    const isLaporan = document.getElementById('jenisLaporan').checked;
    document.getElementById('triwulanWrapper').style.display = isLaporan ? 'block' : 'none';
    updateCardStyles();
    updatePreview();
}

function updateCardStyles() {
    const isLaporan = document.getElementById('jenisLaporan').checked;
    document.getElementById('cardLaporan').classList.toggle('border-warning', isLaporan);
    document.getElementById('cardLaporan').classList.toggle('bg-warning', isLaporan);
    document.getElementById('cardLaporan').classList.toggle('bg-opacity-10', isLaporan);
    document.getElementById('cardPerjanjian').classList.toggle('border-primary', !isLaporan);
    document.getElementById('cardPerjanjian').classList.toggle('bg-primary', !isLaporan);
    document.getElementById('cardPerjanjian').classList.toggle('bg-opacity-10', !isLaporan);
}

function updatePreview() {
    const isLaporan = document.getElementById('jenisLaporan').checked;
    const tahun = document.querySelector('[name=tahun]')?.value || '';
    const triwulan = document.querySelector('[name=triwulan]')?.value || '';
    const tanggal = document.querySelector('[name=tanggal_batas]')?.value || '';
    const pesan = document.querySelector('[name=pesan_tambahan]')?.value || '';

    const twNames = { '1': 'I', '2': 'II', '3': 'III', '4': 'IV' };
    let title, msg;
    if (isLaporan) {
        title = `⚠️ Batas Laporan Kinerja TW ${twNames[triwulan] || triwulan} – ${tahun}`;
        msg = `Segera selesaikan laporan kinerja Triwulan ${twNames[triwulan] || triwulan} Tahun ${tahun}` +
              (tanggal ? ` sebelum ${new Date(tanggal).toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'})}.` : '.');
    } else {
        title = `⚠️ Batas Perjanjian Kinerja – ${tahun}`;
        msg = `Segera buat perjanjian kinerja Tahun ${tahun}` +
              (tanggal ? ` sebelum ${new Date(tanggal).toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'})}.` : '.');
    }
    if (pesan) msg += ' ' + pesan;

    document.getElementById('previewTitle').textContent = title;
    document.getElementById('previewMsg').textContent = msg;
}

// init
document.addEventListener('DOMContentLoaded', function () {
    onJenisChange();
    // listeners
    document.querySelectorAll('[name=jenis],[name=tahun],[name=triwulan],[name=tanggal_batas],[name=pesan_tambahan]')
        .forEach(el => el.addEventListener('change', updatePreview));
    document.querySelector('[name=pesan_tambahan]')?.addEventListener('input', updatePreview);
    if (document.getElementById('recipientSpecific')?.checked) toggleUserSelect(true);
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\admin\notifications\create.blade.php ENDPATH**/ ?>