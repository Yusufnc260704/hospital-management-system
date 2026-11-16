@extends('layouts.app')

@section('title', 'Obat Baru')

@section('content')
    <h1 class="fs-3 fw-bold mb-4">💊 Obat Baru</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('obat.store') }}">
                @csrf
                @include('obat._form')
            </form>
        </div>
    </div>
@endsection
