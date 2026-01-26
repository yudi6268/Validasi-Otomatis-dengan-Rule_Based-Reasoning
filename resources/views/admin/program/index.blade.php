@extends('admin.layout')

@section('title', 'Kelola Program')
@section('page-title', 'Kelola Program')

@section('content')
<style>
    .card-header-program { 
        background: linear-gradient(90deg, #E6F6F2, #CFF2E9);
        padding: 10px 12px;
        border-radius: 6px;
        border-left: 4px solid #00B5A0;
        margin-bottom: 10px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        transition: all 0.2s ease;
    }
    
    .card-header-program:hover {
        background: linear-gradient(90deg, #d4f0ec, #b8e8e0);
    }
    
    .card-header-program .toggle-icon::before {
        content: '▼';
        display: inline-block;
        margin-right: 6px;
        transition: transform 0.3s ease;
        font-size: 0.7rem;
    }
    
    .card-header-program.collapsed .toggle-icon::before {
        transform: rotate(-90deg);
    }
    
    .card-header-kegiatan {
        background: #f0f8ff;
        padding: 8px 10px;
        border-radius: 4px;
        border-left: 4px solid #1E88E5;
        margin: 8px 0 8px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .card-header-kegiatan:hover {
        background: #e8f4ff;
    }
    
    .card-header-kegiatan .toggle-icon::before {
        content: '▼';
        display: inline-block;
        margin-right: 6px;
        transition: transform 0.3s ease;
        font-size: 0.65rem;
    }
    
    .card-header-kegiatan.collapsed .toggle-icon::before {
        transform: rotate(-90deg);
    }
    
    .sub-item {
        background: white;
        padding: 8px 10px;
        margin: 6px 0 6px 40px;
        border-radius: 4px;
        border-left: 3px solid #FFA726;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        border: 1px solid #f0f0f0;
    }
    
    .action-buttons {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }
    
    .action-buttons .btn {
        padding: 0.3rem 0.5rem;
        font-size: 0.75rem;
        white-space: nowrap;
    }
    
    .kegiatan-content {
        border-left: 2px solid #00B5A0;
        padding-left: 0;
        max-height: 5000px;
        overflow: hidden;
        transition: max-height 0.3s ease, opacity 0.3s ease;
        opacity: 1;
    }
    
    .kegiatan-content.collapsed {
        max-height: 0;
        opacity: 0;
    }
    
    .sub-content {
        border-left: 2px solid #1E88E5;
        padding-left: 0;
        max-height: 5000px;
        overflow: hidden;
        transition: max-height 0.3s ease, opacity 0.3s ease;
        opacity: 1;
    }
    
    .sub-content.collapsed {
        max-height: 0;
        opacity: 0;
    }
    
    @media (max-width: 768px) {
        .card-header-program,
        .card-header-kegiatan,
        .sub-item {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .card-header-kegiatan {
            margin-left: 12px;
        }
        
        .sub-item {
            margin-left: 24px;
        }
    }
</style>

<!-- Top Section -->
<div style="display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 200px;">
        <form action="{{ route('admin.program.index') }}" method="GET" style="display: flex; gap: 6px;">
            <input type="text" name="search" class="form-control form-control-sm" 
                   placeholder="Cari program, kegiatan..." value="{{ $search ?? '' }}" 
                   style="font-size: 0.9rem;">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-search"></i>
            </button>
            @if($search ?? false)
                <a href="{{ route('admin.program.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </form>
    </div>
    <a href="{{ route('admin.program.create') }}" class="btn btn-success btn-sm" style="white-space: nowrap;">
        <i class="fas fa-plus"></i> Program Baru
    </a>
</div>

<!-- Data Container -->
<div style="background: white; border-radius: 8px; padding: 12px;">
    @if(empty($programs))
        <div style="text-align: center; color: #999; padding: 30px;">
            <i class="fas fa-folder-open" style="font-size: 3rem; margin-bottom: 10px;"></i>
            <p>Belum ada program</p>
            <a href="{{ route('admin.program.create') }}" class="btn btn-primary btn-sm mt-2">
                <i class="fas fa-plus"></i> Buat Program Pertama
            </a>
        </div>
    @else
        @foreach($programs as $program)
            @php
                $pName = $program['nama_program'] ?? $program['nama'] ?? 'Program';
                $pCode = $program['kode_program'] ?? $program['kode'] ?? '-';
                $pActive = $program['is_active'] ?? true;
                $pId = 'prog-' . $program['id'];
            @endphp
            
            <!-- PROGRAM ITEM -->
            <div style="margin-bottom: 15px;">
                <div class="card-header-program" onclick="toggleProgram(this, '{{ $pId }}');">
                    <div style="flex: 1; display: flex; align-items: center;">
                        <span class="toggle-icon"></span>
                        <span style="background: #00B5A0; color: white; padding: 2px 6px; border-radius: 3px; font-size: 0.7rem; display: inline-block; margin-right: 8px;">
                            {{ Str::limit($pCode, 12) }}
                        </span>
                        <strong style="color: #00B5A0; font-size: 0.95rem;">
                            <i class="fas fa-folder"></i> {{ Str::limit($pName, 45) }}
                        </strong>
                        <span class="badge {{ $pActive ? 'bg-success' : 'bg-secondary' }}" style="font-size: 0.7rem; margin-left: 8px;">
                            {{ $pActive ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <div class="action-buttons" onclick="event.stopPropagation();">
                        <a href="{{ route('admin.program.edit', $program['id']) }}" class="btn btn-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.program.destroy', $program['id']) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" title="Hapus" onclick="return confirm('Yakin?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- KEGIATAN & SUB-KEGIATAN -->
                <div id="{{ $pId }}" class="kegiatan-content">
                    @if(empty($program['kegiatan']))
                        <div style="margin: 10px 0 10px 20px; color: #999; font-size: 0.85rem;">
                            Belum ada kegiatan. 
                            <a href="{{ route('admin.kegiatan.create', $program['id']) }}">Tambah?</a>
                        </div>
                    @else
                        @foreach($program['kegiatan'] as $kegiatan)
                            @php
                                $kName = $kegiatan['nama_kegiatan'] ?? $kegiatan['nama'] ?? 'Kegiatan';
                                $kCode = $kegiatan['kode_kegiatan'] ?? $kegiatan['kode'] ?? '-';
                                $kActive = $kegiatan['is_active'] ?? true;
                                $kId = 'keg-' . $kegiatan['id'];
                            @endphp
                            
                            <div class="card-header-kegiatan" onclick="toggleKegiatan(this, '{{ $kId }}');">
                                <div style="flex: 1; display: flex; align-items: center;">
                                    <span class="toggle-icon"></span>
                                    <span style="background: #1E88E5; color: white; padding: 2px 5px; border-radius: 3px; font-size: 0.65rem; display: inline-block; margin-right: 6px;">
                                        {{ Str::limit($kCode, 10) }}
                                    </span>
                                    <strong style="color: #1E88E5; font-size: 0.85rem;">
                                        <i class="fas fa-list-ul"></i> {{ Str::limit($kName, 35) }}
                                    </strong>
                                    <span class="badge {{ $kActive ? 'bg-success' : 'bg-secondary' }}" style="font-size: 0.65rem; margin-left: 6px;">
                                        {{ $kActive ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                                <div class="action-buttons" onclick="event.stopPropagation();">
                                    <a href="{{ route('admin.sub-kegiatan.create', $kegiatan['id']) }}" class="btn btn-info" title="Tambah Sub">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                    <a href="{{ route('admin.kegiatan.edit', $kegiatan['id']) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.kegiatan.destroy', $kegiatan['id']) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Hapus" onclick="return confirm('Yakin?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <!-- SUB-KEGIATAN LIST -->
                            <div id="{{ $kId }}" class="sub-content">
                                @if(empty($kegiatan['sub_kegiatan']))
                                    <div style="margin: 8px 0 8px 40px; color: #999; font-size: 0.8rem;">
                                        Belum ada sub. 
                                        <a href="{{ route('admin.sub-kegiatan.create', $kegiatan['id']) }}">Tambah?</a>
                                    </div>
                                @else
                                    @foreach($kegiatan['sub_kegiatan'] as $sub)
                                        @php
                                            $sName = $sub['nama_sub_kegiatan'] ?? $sub['nama'] ?? 'Sub Kegiatan';
                                            $sCode = $sub['kode_sub_kegiatan'] ?? $sub['kode'] ?? '-';
                                            $sActive = $sub['is_active'] ?? true;
                                        @endphp
                                        
                                        <div class="sub-item">
                                            <div style="flex: 1;">
                                                <span style="background: #FFA726; color: white; padding: 2px 5px; border-radius: 3px; font-size: 0.6rem; display: inline-block; margin-right: 6px;">
                                                    {{ Str::limit($sCode, 8) }}
                                                </span>
                                                <span style="color: #555; font-size: 0.8rem;">
                                                    <i class="fas fa-circle" style="font-size: 0.5rem;"></i> {{ Str::limit($sName, 30) }}
                                                </span>
                                                <span class="badge {{ $sActive ? 'bg-success' : 'bg-secondary' }}" style="font-size: 0.6rem; margin-left: 6px;">
                                                    {{ $sActive ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </div>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.sub-kegiatan.edit', $sub['id']) }}" class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.sub-kegiatan.destroy', $sub['id']) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Hapus" onclick="return confirm('Yakin?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>

<script>
    function toggleProgram(element, contentId) {
        const content = document.getElementById(contentId);
        element.classList.toggle('collapsed');
        content.classList.toggle('collapsed');
    }

    function toggleKegiatan(element, contentId) {
        const content = document.getElementById(contentId);
        element.classList.toggle('collapsed');
        content.classList.toggle('collapsed');
    }

    // Auto-expand kegiatan if redirect from create sub-kegiatan
    document.addEventListener('DOMContentLoaded', function() {
        const expandKegiatanId = '{{ session("expandKegiatan") }}';
        if (expandKegiatanId) {
            const element = document.getElementById('keg-' + expandKegiatanId);
            if (element) {
                // Find parent program and expand it too
                let current = element.parentElement;
                while (current) {
                    if (current.classList.contains('kegiatan-content')) {
                        // Expand program
                        const progHeader = current.previousElementSibling;
                        if (progHeader && progHeader.classList.contains('card-header-program')) {
                            progHeader.classList.remove('collapsed');
                            current.classList.remove('collapsed');
                        }
                        break;
                    }
                    current = current.parentElement;
                }
                
                // Expand kegiatan
                element.previousElementSibling.classList.remove('collapsed');
                element.classList.remove('collapsed');
                
                // Scroll to view
                setTimeout(() => {
                    element.previousElementSibling.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 200);
            }
        }
    });
</script>
@endsection
