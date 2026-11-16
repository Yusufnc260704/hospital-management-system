@extends('layouts.app')

@section('title', 'Pasien Baru')

@section('content')
    <h1 class="fs-3 fw-bold mb-4">👥 Pasien Baru</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="alert alert-info py-2">
                Pasien akan mendapatkan <strong>No. RM otomatis</strong>.
                @isset($noRmLanjut)
                    (Berikutnya: <strong>{{ $noRmLanjut }}</strong>)
                @endisset
            </div>

            <form method="POST" action="{{ route('pasien.store') }}">
                @csrf
                @include('pasien._form')
            </form>
        </div>
    </div>
@endsection
