@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3">Master Pegawai</h1>
        <p class="text-muted">Kelola daftar pegawai dan struktur organisasi.</p>
    </div>
    <a href="{{ route('pegawai.create') }}" class="btn btn-primary">Tambah Pegawai</a>
</div>

@include('components.alert')

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="pegawaiTable" class="table table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th>Akun Login</th>
                        <th>Tanggal Masuk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pegawai as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nip }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->divisi?->nama_divisi }}</td>
                            <td>{{ $item->jabatan?->nama_jabatan }}</td>
                            <td>{{ ucfirst($item->status->value) }}</td>
                            <td>
                                @if($item->user_id)
                                    <span class="badge bg-success">Ada</span>
                                @else
                                    <span class="badge bg-secondary">Belum</span>
                                @endif
                            </td>
                            <td>{{ $item->tanggal_masuk?->format('Y-m-d') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('pegawai.edit', $item) }}" class="btn btn-secondary" title="Edit">Edit</a>
                                    @if(!$item->user_id)
                                        <a href="{{ route('pegawai.createAccount', $item) }}" class="btn btn-success" title="Buat Akun Login">Akun</a>
                                    @endif
                                    <form action="{{ route('pegawai.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pegawai ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit" title="Hapus">Hapus</button>
                                    </form>
                                </div>
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
        $('#pegawaiTable').DataTable({
            responsive: true,
            pageLength: 10,
        });
    });
</script>
@endpush
