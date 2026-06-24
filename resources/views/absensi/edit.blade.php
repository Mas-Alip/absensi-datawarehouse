<!-- edit absensi -->
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3">Edit Absensi</h1>
        <p class="text-muted">Ubah catatan absensi.</p>
    </div>
    <a href="{{ route('absensi.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

@include('components.alert')

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('absensi.update', $absensi) }}">
            @csrf
            @method('PUT')

            @include('absensi._form')
        </form>
    </div>
</div>
@endsection
