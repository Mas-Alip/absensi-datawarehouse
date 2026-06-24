<div class="row g-3">
    <div class="col-md-6">
        <label for="pegawai_id" class="form-label">Pegawai</label>
        <select id="pegawai_id" name="pegawai_id" class="form-select @error('pegawai_id') is-invalid @enderror" required>
            <option value="">Pilih Pegawai</option>
            @foreach($pegawai as $p)
                <option value="{{ $p->id }}" {{ (old('pegawai_id', $absensi->pegawai_id ?? '') == $p->id) ? 'selected' : '' }}>{{ $p->nip }} - {{ $p->nama }}</option>
            @endforeach
        </select>
        @error('pegawai_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="tanggal" class="form-label">Tanggal</label>
        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', isset($absensi) ? $absensi->tanggal?->format('Y-m-d') : '') }}" class="form-control @error('tanggal') is-invalid @enderror" required>
        @error('tanggal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row g-3 mt-3">
    <div class="col-md-6">
        <label for="jam_masuk" class="form-label">Jam Masuk</label>
        <input type="time" id="jam_masuk" name="jam_masuk" value="{{ old('jam_masuk', $absensi->jam_masuk ?? '') }}" class="form-control @error('jam_masuk') is-invalid @enderror">
        @error('jam_masuk')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="jam_keluar" class="form-label">Jam Keluar</label>
        <input type="time" id="jam_keluar" name="jam_keluar" value="{{ old('jam_keluar', $absensi->jam_keluar ?? '') }}" class="form-control @error('jam_keluar') is-invalid @enderror">
        @error('jam_keluar')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row g-3 mt-3">
    <div class="col-md-6">
        <label for="status_kehadiran" class="form-label">Status Kehadiran</label>
        <select id="status_kehadiran" name="status_kehadiran" class="form-select @error('status_kehadiran') is-invalid @enderror" required>
            <option value="">Pilih Status</option>
            <option value="hadir" {{ old('status_kehadiran', $absensi->status_kehadiran?->value ?? '') === 'hadir' ? 'selected' : '' }}>Hadir</option>
            <option value="izin" {{ old('status_kehadiran', $absensi->status_kehadiran?->value ?? '') === 'izin' ? 'selected' : '' }}>Izin</option>
            <option value="sakit" {{ old('status_kehadiran', $absensi->status_kehadiran?->value ?? '') === 'sakit' ? 'selected' : '' }}>Sakit</option>
            <option value="alpa" {{ old('status_kehadiran', $absensi->status_kehadiran?->value ?? '') === 'alpa' ? 'selected' : '' }}>Alpa</option>
        </select>
        @error('status_kehadiran')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="keterangan" class="form-label">Keterangan</label>
        <input type="text" id="keterangan" name="keterangan" value="{{ old('keterangan', $absensi->keterangan ?? '') }}" class="form-control @error('keterangan') is-invalid @enderror">
        @error('keterangan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mt-4">
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('absensi.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
</div>
