@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3">Tambah Pegawai</h1>
        <p class="text-muted">Tambahkan data pegawai baru untuk master data.</p>
    </div>
    <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

@include('components.alert')

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('pegawai.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nip" class="form-label">NIP</label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip') }}" class="form-control @error('nip') is-invalid @enderror" required>
                    @error('nip')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <label for="divisi_id" class="form-label">Divisi</label>
                    <select id="divisi_id" name="divisi_id" class="form-select @error('divisi_id') is-invalid @enderror" required>
                        <option value="">Pilih Divisi</option>
                        @foreach($divisi as $item)
                            <option value="{{ $item->id }}" {{ old('divisi_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_divisi }}</option>
                        @endforeach
                    </select>
                    @error('divisi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="jabatan_id" class="form-label">Jabatan</label>
                    <select id="jabatan_id" name="jabatan_id" class="form-select @error('jabatan_id') is-invalid @enderror" required>
                        <option value="">Pilih Jabatan</option>
                        @foreach($jabatan as $item)
                            <option value="{{ $item->id }}" {{ old('jabatan_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_jabatan }}</option>
                        @endforeach
                    </select>
                    @error('jabatan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="male" {{ old('jenis_kelamin') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('jenis_kelamin') === 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label">Status Pegawai</label>
                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="">Pilih Status</option>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                    <input type="date" id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" class="form-control @error('tanggal_masuk') is-invalid @enderror">
                    @error('tanggal_masuk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="no_hp" class="form-label">No. HP</label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" class="form-control @error('no_hp') is-invalid @enderror">
                    @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-12">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button class="btn btn-primary mt-4">Simpan</button>
        </form>
    </div>
</div>
@endsection
