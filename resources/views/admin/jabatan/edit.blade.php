@extends('admin.layout')

@section('content')
<form method="POST" action="{{ route('admin.jabatan.update', $jabatan['id']) }}">
@csrf
@method('PUT')

<input name="nama_jabatan" class="form-control mb-2"
       value="{{ $jabatan['nama_jabatan'] }}">

<textarea name="tugas" class="form-control mb-2">{{ $jabatan['tugas'] }}</textarea>

@foreach($jabatan['fungsi'] ?? [] as $f)
    <input name="fungsi[]" class="form-control mb-1" value="{{ $f }}">
@endforeach

<label>
    <input type="checkbox" name="is_active" value="1"
        {{ $jabatan['is_active'] ? 'checked' : '' }}> Aktif
</label>

<button class="btn btn-primary mt-3">Update</button>
</form>
@endsection
