@extends('layouts.app')

@section('title', 'Edit Pasien')

@section('content')
    <h1 class="fs-3 fw-bold mb-4">✏️ Edit Pasien: {{ $pasien->nama }}</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('pasien.update', $pasien) }}">
                @csrf
                @method('PUT')
                @include('pasien._form', ['pasien' => $pasien])
            </form>
        </div>
    </div>
@endsection
