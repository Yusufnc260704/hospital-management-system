@extends('layouts.app')

@section('title', 'Edit Obat')

@section('content')
    <h1 class="fs-3 fw-bold mb-4">✏️ Edit Obat: {{ $obat->nama }}</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('obat.update', $obat) }}">
                @csrf
                @method('PUT')
                @include('obat._form', ['obat' => $obat])
            </form>
        </div>
    </div>
@endsection
