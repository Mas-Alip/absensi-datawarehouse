@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3">Master Divisi</h1>
        <p class="text-muted">Kelola struktur divisi di perusahaan.</p>
    </div>
    <a href="{{ route('divisi.create') }}" class="btn btn-primary">Tambah Divisi</a>
</div>

@include('components.alert')

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="divisiTable" class="table table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Divisi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($divisis as $index => $divisi)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $divisi->nama_divisi }}</td>
                            <td>
                                <a href="{{ route('divisi.edit', $divisi) }}" class="btn btn-sm btn-secondary me-1">Edit</a>
                                <form action="{{ route('divisi.destroy', $divisi) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus divisi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#divisiTable').DataTable({
            responsive: true,
            pageLength: 10,
        });
    });
</script>
@endpush
