@if(isset($jabatan['status']) && isset($jabatan['body']))
    <div class="alert alert-danger">
        <b>Supabase Error (Status {{ $jabatan['status'] }}):</b><br>
        <pre style="white-space:pre-wrap;word-break:break-all;">{{ $jabatan['body'] }}</pre>
    </div>
@elseif(empty($jabatan) || (is_array($jabatan) && count($jabatan) === 0))
    <div class="alert alert-warning text-center">Data tidak ditemukan.</div>
@else
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Jabatan</th>
                <th>Tugas</th>
                <th>Fungsi</th>
                <th>Membawahi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($jabatan as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item['nama_jabatan'] }}</td>
                <td>{{ \Illuminate\Support\Str::limit($item['tugas'] ?? '-', 60) }}</td>
                <td>
                    @php
                        $fungsi = $item['fungsi'] ?? [];
                        if (is_string($fungsi)) {
                            $fungsi = json_decode($fungsi, true) ?: [];
                        }
                    @endphp
                    @if(!empty($fungsi) && is_array($fungsi))
                        <ul class="mb-0">
                            @foreach($fungsi as $f)
                                <li>{{ $f }}</li>
                            @endforeach
                        </ul>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @php
                        $membawahi = $item['membawahi'] ?? [];
                        if (is_string($membawahi)) {
                            $membawahi = json_decode($membawahi, true) ?: [];
                        }
                    @endphp
                    @if(!empty($membawahi) && is_array($membawahi))
                        <ul class="mb-0">
                            @foreach($membawahi as $unit)
                                <li>{{ $unit }}</li>
                            @endforeach
                        </ul>
                    @else
                        -
                    @endif
                </td>
                <td>
                    {{ $item['is_active'] ? 'Aktif' : 'Nonaktif' }}
                </td>
                <td>
                    <a href="{{ route('admin.jabatan.edit', $item['id']) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.jabatan.destroy', $item['id']) }}"
                          method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-danger btn-sm"
                                onclick="showDeleteConfirmModal(this.closest('form'), 'Apakah Anda yakin ingin menghapus jabatan {{ $item['nama_jabatan'] }}?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
