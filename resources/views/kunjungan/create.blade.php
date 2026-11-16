@extends('layouts.app')

@section('title', 'Kunjungan Baru')

@section('content')
    <h1 class="fs-3 fw-bold mb-4">📝 Pendaftaran Kunjungan Baru</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('kunjungan.store') }}">
                @csrf
                @include('kunjungan._form')
            </form>
        </div>
    </div>
@endsection
