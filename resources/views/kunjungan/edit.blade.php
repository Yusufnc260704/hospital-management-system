@extends('layouts.app')

@section('title', 'Edit Kunjungan')

@section('content')
    <h1 class="fs-3 fw-bold mb-4">✏️ Edit Kunjungan #{{ $kunjungan->id }}</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('kunjungan.update', $kunjungan) }}">
                @csrf
                @method('PUT')
                @include('kunjungan._form', ['kunjungan' => $kunjungan])
            </form>
        </div>
    </div>
@endsection
