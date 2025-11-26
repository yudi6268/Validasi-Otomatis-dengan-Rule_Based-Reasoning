@extends('layouts.app')

@section('title', 'Perjanjian')
@section('header_title', 'Perjanjian')

@section('back')
<a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left header-icon"></i></a>
@endsection

@section('content')
<div style="text-align:center; margin-top:-20px;">
  <h2 style="font-weight:700; margin-bottom:35px;">DAFTAR DATA FORM PERJANJIAN</h2>

  <div class="card-container">
    <div class="status-card">
      <h2>0</h2>
      <p>Laporan Dikirim</p>
      <button class="btn-view btn-green">Lihat</button>
    </div>

    <div class="status-card">
      <h2>0</h2>
      <p>Disetujui</p>
      <button class="btn-view btn-yellow">Lihat</button>
    </div>

    <div class="status-card">
      <h2>0</h2>
      <p>Ditolak</p>
      <button class="btn-view btn-red">Lihat</button>
    </div>

    <div class="status-card">
      <h2>0</h2>
      <p>Menunggu</p>
      <button class="btn-view btn-blue">Lihat</button>
    </div>
  </div>

 <button class="btn-add" onclick="window.location='{{ route('perjanjian.create') }}'">+ Tambah Perjanjian</button>
</div>
@endsection