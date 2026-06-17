@extends('admin.layout')

@section('title', 'Kirim Notifikasi Deadline')
@section('page-title', 'Kirim Notifikasi')

@section('content')
<div class="data-table" style="max-width: 720px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1"><i class="fas fa-bell"></i> Notifikasi</h5>
            <small class="text-muted">Notifikasi ini akan muncul di sudut kiri atas dashboard pengguna sebagai pengingat batas waktu.</small>
        </div>
        <a href="{{ route('admin.notifications.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.notifications.store') }}" method="POST">
        @csrf

        {{-- JENIS NOTIFIKASI --}}
        <div class="mb-4">
            <label class="form-label fw-bold">Jenis Notifikasi <span class="text-danger">*</span></label>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="d-flex align-items-center gap-3 p-3 border rounded-3 cursor-pointer {{ old('jenis') === 'laporan' ? 'border-warning bg-warning bg-opacity-10' : 'border-secondary-subtle' }}" style="cursor:pointer;" id="cardLaporan">
                        <input class="form-check-input mt-0 flex-shrink-0" type="radio" name="jenis" value="laporan"
                               id="jenisLaporan" {{ old('jenis', 'laporan') === 'laporan' ? 'checked' : '' }}
                               onchange="onJenisChange()">
                        <div>
                            <div class="fw-bold"><i class="fas fa-chart-line text-warning"></i> Batas Laporan Kinerja</div>
                            <small class="text-muted">Ingatkan pengguna untuk membuat laporan kinerja triwulan.</small>
                        </div>
                    </label>
                </div>
                <div class="col-md-6">
                    <label class="d-flex align-items-center gap-3 p-3 border rounded-3 cursor-pointer {{ old('jenis') === 'perjanjian' ? 'border-primary bg-primary bg-opacity-10' : 'border-secondary-subtle' }}" style="cursor:pointer;" id="cardPerjanjian">
                        <input class="form-check-input mt-0 flex-shrink-0" type="radio" name="jenis" value="perjanjian"
                               id="jenisPerjanjian" {{ old('jenis') === 'perjanjian' ? 'checked' : '' }}
                               onchange="onJenisChange()">
                        <div>
                            <div class="fw-bold"><i class="fas fa-file-contract text-primary"></i> Batas Perjanjian Kinerja</div>
                            <small class="text-muted">Ingatkan pengguna untuk membuat perjanjian kinerja tahunan.</small>
                        </div>
                    </label>
                </div>
            </div>
            @error('jenis')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        {{-- TAHUN --}}
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Tahun <span class="text-danger">*</span></label>
                <select name="tahun" class="form-select">
                    @for ($y = date('Y') - 1; $y <= date('Y') + 2; $y++)
                        <option value="{{ $y }}" {{ old('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                @error('tahun')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            {{-- TRIWULAN (hanya untuk laporan) --}}
            <div class="col-md-4" id="triwulanWrapper">
                <label class="form-label fw-bold">Triwulan <span class="text-danger">*</span></label>
                <select name="triwulan" class="form-select" id="triwulanSelect">
                    <option value="1" {{ old('triwulan', '1') == '1' ? 'selected' : '' }}>Triwulan I (Jan – Mar)</option>
                    <option value="2" {{ old('triwulan') == '2' ? 'selected' : '' }}>Triwulan II (Apr – Jun)</option>
                    <option value="3" {{ old('triwulan') == '3' ? 'selected' : '' }}>Triwulan III (Jul – Sep)</option>
                    <option value="4" {{ old('triwulan') == '4' ? 'selected' : '' }}>Triwulan IV (Okt – Des)</option>
                </select>
                @error('triwulan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            {{-- TANGGAL BATAS --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">Tanggal Batas Akhir <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_batas" class="form-control"
                       value="{{ old('tanggal_batas') }}" min="{{ date('Y-m-d') }}">
                @error('tanggal_batas')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- URGENSI --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Tingkat Urgensi <span class="text-danger">*</span></label>
            <div class="d-flex gap-3 flex-wrap">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" value="info" id="typeInfo"
                           {{ old('type', 'warning') === 'info' ? 'checked' : '' }}>
                    <label class="form-check-label" for="typeInfo">
                        <span class="badge bg-info">ℹ️ Info</span> — Pemberitahuan biasa
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" value="warning" id="typeWarning"
                           {{ old('type', 'warning') === 'warning' ? 'checked' : '' }}>
                    <label class="form-check-label" for="typeWarning">
                        <span class="badge bg-warning text-dark">⚠️ Peringatan</span> — Mendekati batas waktu
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" value="danger" id="typeDanger"
                           {{ old('type') === 'danger' ? 'checked' : '' }}>
                    <label class="form-check-label" for="typeDanger">
                        <span class="badge bg-danger">🚨 Mendesak</span> — Segera sebelum batas waktu
                    </label>
                </div>
            </div>
            @error('type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        {{-- PESAN TAMBAHAN --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Pesan Tambahan <small class="text-muted fw-normal">(opsional)</small></label>
            <textarea name="pesan_tambahan" class="form-control" rows="3"
                      placeholder="Contoh: Harap segera hubungi bagian kepegawaian jika ada kesulitan...">{{ old('pesan_tambahan') }}</textarea>
        </div>

        {{-- PENERIMA --}}
        <div class="mb-4">
            <label class="form-label fw-bold">Penerima <span class="text-danger">*</span></label>
            <div class="d-flex gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="recipient_type" value="all"
                           id="recipientAll" {{ old('recipient_type', 'all') === 'all' ? 'checked' : '' }}
                           onchange="toggleUserSelect(false)">
                    <label class="form-check-label" for="recipientAll">
                        <i class="fas fa-users text-success"></i> Semua Pengguna
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="recipient_type" value="specific"
                           id="recipientSpecific" {{ old('recipient_type') === 'specific' ? 'checked' : '' }}
                           onchange="toggleUserSelect(true)">
                    <label class="form-check-label" for="recipientSpecific">
                        <i class="fas fa-user text-primary"></i> Pengguna Tertentu
                    </label>
                </div>
            </div>
            <div class="mt-2" id="userSelectWrapper" style="display:{{ old('recipient_type') === 'specific' ? 'block' : 'none' }};">
                <select name="user_ids[]" class="form-select" id="userSelect" multiple size="6">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ in_array($user->id, old('user_ids', [])) ? 'selected' : '' }}>
                            {{ $user->nama }} — {{ $user->jabatan ?? 'Tanpa jabatan' }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Tahan Ctrl/Cmd atau Shift untuk memilih lebih dari satu pengguna.</div>
            </div>
            @error('recipient_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            @error('user_ids')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            @error('user_ids.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        {{-- PREVIEW --}}
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
            <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@push('scripts')
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
@endpush

@endsection