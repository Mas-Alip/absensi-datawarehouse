<!-- absensi index -->
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3">Daftar Absensi</h1>
        <p class="text-muted">Kelola catatan absensi pegawai dengan cepat.</p>
    </div>
    <a href="{{ route('absensi.create') }}" class="btn btn-primary">Tambah Absensi</a>
</div>

@include('components.alert')

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('absensi.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Cari Nama / NIP</label>
                <input type="search" name="search" value="{{ old('search', $search ?? '') }}" class="form-control" placeholder="Masukkan nama atau NIP">
            </div>
            <div class="col-md-3">
                <label class="form-label">Filter Kehadiran</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="hadir" {{ isset($status) && $status === 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="izin" {{ isset($status) && $status === 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ isset($status) && $status === 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="alpa" {{ isset($status) && $status === 'alpa' ? 'selected' : '' }}>Alpa</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Urutkan</label>
                <select name="sort" class="form-select">
                    <option value="newest" {{ isset($sort) && $sort === 'newest' ? 'selected' : '' }}>Tanggal Terbaru</option>
                    <option value="oldest" {{ isset($sort) && $sort === 'oldest' ? 'selected' : '' }}>Tanggal Terlama</option>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">Terapkan</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Tanggal</th>
                        <th>Pegawai</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Status Kehadiran</th>
                        <th>Status Keterlambatan</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $item)
                        @php
                            $statusKeterlambatan = $item->status_keterlambatan?->value ?? $item->status_keterlambatan;
                            $statusKehadiran = $item->status_kehadiran?->value ?? $item->status_kehadiran;
                        @endphp
                        <tr>
                            <td>{{ $absensi->firstItem() + $loop->index }}</td>
                            <td>{{ $item->tanggal?->format('Y-m-d') }}</td>
                            <td>{{ $item->pegawai?->nip }} - {{ $item->pegawai?->nama }}</td>
                            <td>{{ $item->jam_masuk ?? '-' }}</td>
                            <td>{{ $item->jam_keluar ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info text-dark">{{ ucfirst($statusKehadiran) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $statusKeterlambatan === 'late' ? 'danger' : 'success' }}">
                                    {{ $statusKeterlambatan === 'late' ? 'Terlambat' : 'Tepat Waktu' }}
                                </span>
                            </td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td>
                                <a href="{{ route('absensi.edit', $item) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('absensi.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">Data absensi tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Menampilkan {{ $absensi->firstItem() ?? 0 }} sampai {{ $absensi->lastItem() ?? 0 }} dari {{ $absensi->total() }} data
            </div>
            <div>
                {{ $absensi->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
