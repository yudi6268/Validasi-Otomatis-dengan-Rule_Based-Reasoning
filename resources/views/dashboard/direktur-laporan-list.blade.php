<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }} — Direktur</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #E3F8F6; min-height: 100vh; display: flex; flex-direction: column; }

        .top-header {
            background: #fff;
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
            position: sticky; top: 0; z-index: 100;
        }
        .top-header .back-btn {
            color: #009970; font-size: 20px; text-decoration: none;
            width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
        }
        .top-header h1 { font-size: 17px; font-weight: 700; color: #009970; text-align: center; flex: 1; }
        .top-header .right-icons { display: flex; align-items: center; gap: 14px; }
        .top-header .right-icons a { color: #009970; font-size: 20px; text-decoration: none; }

        .page-body { flex: 1; padding: 20px 16px 100px; max-width: 760px; margin: 0 auto; width: 100%; }

        .lk-card {
            background: #fff; border-radius: 14px; padding: 20px;
            margin-bottom: 14px; display: flex; align-items: center; gap: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08); transition: box-shadow .2s;
        }
        .lk-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,.13); }
        .lk-icon {
            width: 64px; height: 64px; background: #2196F3; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .lk-icon i { color: #fff; font-size: 28px; }
        .lk-info { flex: 1; min-width: 0; }
        .lk-title { font-size: 16px; font-weight: 700; color: #222; margin-bottom: 5px; }
        .lk-badge {
            display: inline-block; padding: 4px 13px; border-radius: 12px;
            font-size: 13px; font-weight: 600; margin-bottom: 5px;
        }
        .badge-menunggu  { background: #fff3cd; color: #FFA500; }
        .badge-disetujui { background: #d4edda; color: #009970; }
        .badge-ditolak   { background: #f8d7da; color: #DC3545; }
        .badge-terkirim  { background: #e2e8f0; color: #475569; }
        .lk-sub { font-size: 13px; color: #888; }
        .lk-action { flex-shrink: 0; }
        .view-btn {
            background: none; border: none; cursor: pointer;
            font-size: 26px; color: #2196F3; padding: 6px; line-height: 1; transition: opacity .2s;
        }
        .view-btn:hover { opacity: .7; }

        .empty { text-align: center; padding: 60px 20px; color: #bbb; }
        .empty i { font-size: 56px; margin-bottom: 16px; display: block; }

        footer {
            background: #fff; border-top: 1px solid #dbe2ea;
            text-align: center; font-size: 13px; font-weight: 700; line-height: 1.4;
            color: #1B2A41; padding: 14px 12px; font-family: 'Segoe UI', Tahoma, sans-serif;
            position: fixed; bottom: 0; left: 0; right: 0;
        }
    </style>
</head>
<body>

    <div class="top-header">
        <a href="{{ route('dashboard.direktur', ['panel' => 'laporan']) }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1>{{ $pageTitle }}</h1>
        <div class="right-icons">
            <a href="{{ route('dashboard.direktur', ['panel' => 'profil']) }}" title="Profil">
                <i class="fas fa-user-circle"></i>
            </a>
            <a href="#" onclick="document.getElementById('logoutFrm').submit();" title="Keluar">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>

    <form id="logoutFrm" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>

    <div class="page-body">
        @forelse($items as $lk)
            @php
                $badgeClass = ['disetujui'=>'badge-disetujui','menunggu'=>'badge-menunggu','ditolak'=>'badge-ditolak','terkirim'=>'badge-terkirim'][$lk['status']] ?? 'badge-terkirim';
                $badgeLabel = ['disetujui'=>'Disetujui','menunggu'=>'Menunggu','ditolak'=>'Ditolak','terkirim'=>'Terkirim'][$lk['status']] ?? ucfirst($lk['status']);
            @endphp
            <div class="lk-card">
                <div class="lk-icon"><i class="fas fa-file-alt"></i></div>
                <div class="lk-info">
                    <div class="lk-title">Laporan Kinerja Triwulan {{ $lk['triwulan'] }} {{ $lk['tahun'] }}</div>
                    <span class="lk-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                    <div class="lk-sub">{{ $lk['pihak1_name'] }}</div>
                </div>
                <div class="lk-action">
                    <button class="view-btn" title="Lihat"
                        onclick="window.open('{{ $lk['view_url'] }}', '_blank')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="empty">
                <i class="fas fa-inbox"></i>
                Tidak ada data laporan.
            </div>
        @endforelse
    </div>

    <footer>© {{ date('Y') }} RSUD Bangil | Validasi Otomatis Laporan Kinerja RSUD Bangil</footer>

</body>
</html>
