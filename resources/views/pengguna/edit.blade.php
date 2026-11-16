@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="kepala-halaman">
        <div>
            <h1>Edit Pengguna</h1>
            <div class="sub">{{ $pengguna->name }} — {{ $pengguna->email }}</div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            @include('pengguna._form')
        </div>
    </div>
@endsection
