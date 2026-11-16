@extends('layouts.app')

@section('title', 'Pengguna Baru')

@section('content')
    <div class="kepala-halaman">
        <div>
            <h1>Pengguna Baru</h1>
            <div class="sub">Buat akun pegawai dan tentukan perannya.</div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            @include('pengguna._form')
        </div>
    </div>
@endsection
