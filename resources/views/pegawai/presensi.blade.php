@extends('layouts.app')

@section('title', 'Presensi Saya')

@php
    $statusToday = $todayAttendance?->status_kehadiran?->value;
@endphp

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="h3 mb-1">Presensi Saya</h1>
                <p class="text-muted">Pilih jenis presensi yang sesuai untuk hari ini.</p>
            </div>
            <a href="{{ route('pegawai.dashboard') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
        </div>
    </div>

    @include('components.alert')

    <div class="row gy-3">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h5 class="card-title">Ringkasan Presensi Hari Ini</h5>
                    <div class="mb-3">
                        <span class="text-muted">Tanggal</span>
                        <div class="fs-5 fw-semibold">{{ $today->format('l, d F Y') }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted">Status Presensi</span>
                        <div class="fs-5 fw-semibold">
                            @if(!$todayAttendance)
                                Belum presensi
                            @elseif($statusToday === 'hadir' && !$todayAttendance->jam_keluar)
                                Hadir - menunggu pulang
                            @elseif($statusToday === 'hadir' && $todayAttendance->jam_keluar)
                                Presensi hari ini selesai
                            @elseif($statusToday === 'izin')
                                Izin
                            @elseif($statusToday === 'sakit')
                                Sakit
                            @else
                                {{ $todayAttendance->status_kehadiran?->label() ?? ucfirst($todayAttendance->status_kehadiran ?? '-') }}
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted">Jam Masuk</span>
                        <div class="fs-5 fw-semibold">{{ $todayAttendance?->jam_masuk ? date('H:i:s', strtotime($todayAttendance->jam_masuk)) : '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted">Jam Keluar</span>
                        <div class="fs-5 fw-semibold">{{ $todayAttendance?->jam_keluar ? date('H:i:s', strtotime($todayAttendance->jam_keluar)) : '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted">Keterangan</span>
                        <div class="fs-5 fw-semibold">{{ $todayAttendance?->keterangan ?? '-' }}</div>
                    </div>
                    @if($todayAttendance && $statusToday === 'hadir')
                        <div class="mb-3">
                            <span class="text-muted">Status Kedisiplinan</span>
                            <div class="fs-5 fw-semibold">{{ $todayAttendance->status_keterlambatan?->label() ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted">Keterlambatan</span>
                            <div class="fs-5 fw-semibold">{{ $todayAttendance->menit_terlambat ? $todayAttendance->menit_terlambat . ' Menit' : '0 Menit' }}</div>
                        </div>
                    @endif
                    <div class="mb-3">
                        <span class="text-muted">Bukti</span>
                        <div class="fs-5 fw-semibold">
                            @if($todayAttendance?->bukti_file)
                                <a href="{{ asset('storage/' . $todayAttendance->bukti_file) }}" target="_blank" class="link-primary">Lihat Bukti</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    @if($todayAttendance?->latitude && $todayAttendance?->longitude)
                        <div class="mb-3">
                            <span class="text-muted">Lokasi</span>
                            <div class="fs-5 fw-semibold">
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $todayAttendance->latitude }},{{ $todayAttendance->longitude }}" target="_blank" class="link-primary">Lihat Lokasi</a>
                            </div>
                        </div>
                    @endif

                    @if($todayAttendance?->foto_selfie)
                        <div class="mb-3">
                            <span class="text-muted">Selfie</span>
                            <div class="fs-5 fw-semibold">
                                <a href="{{ asset('storage/' . $todayAttendance->foto_selfie) }}" target="_blank" class="link-primary">Lihat Selfie</a>
                            </div>
                        </div>
                    @endif

                    @if(!$todayAttendance)
                        <div class="mt-4">
                            <div class="d-grid gap-2">
                                <a href="#presensi-options" class="btn btn-outline-primary">Pilih Jenis Presensi</a>
                            </div>
                        </div>
                    @elseif($statusToday === 'hadir' && !$todayAttendance->jam_keluar)
                        <form action="{{ route('pegawai.presensi.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">Presensi Pulang</button>
                        </form>
                    @else
                        <div class="alert alert-success mb-0">Presensi hari ini selesai.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            @if(!$todayAttendance)
                <div id="presensi-options" class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="card border-success rounded-4 h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-3">
                                    <span class="badge bg-success">Hadir</span>
                                </div>
                                <h5 class="card-title">Presensi Hadir</h5>
                                <p class="card-text text-muted">Catat kehadiran dengan selfie dan kamera real-time sebelum submit.</p>

                                <form id="hadirForm" action="{{ route('pegawai.presensi.checkin') }}" method="POST" class="mt-auto">
                                    @csrf
                                    <input type="hidden" name="jenis_presensi" value="hadir">
                                    <input type="hidden" id="foto_selfie" name="foto_selfie" value="{{ old('foto_selfie') }}">
                                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">

                                    <div class="d-grid gap-2 mb-3">
                                        <button id="openCameraBtn" type="button" class="btn btn-outline-primary">Buka Kamera</button>
                                        <button id="capturePhotoBtn" type="button" class="btn btn-outline-secondary" disabled>Ambil Foto</button>
                                        <button id="retakePhotoBtn" type="button" class="btn btn-outline-danger d-none">Ulangi</button>
                                        <button id="usePhotoBtn" type="button" class="btn btn-success d-none">Gunakan Foto</button>
                                        <button id="submitHadirBtn" type="submit" class="btn btn-success" disabled>Presensi Hadir</button>
                                    </div>

                                    <div id="gpsStatus" class="alert alert-danger d-flex align-items-start gap-2 mb-3" role="alert">
                                        <i class="bi bi-geo-alt-fill fs-5 mt-1"></i>
                                        <div>
                                            <strong>📍 Status GPS</strong>
                                            <div id="gpsStatusText" class="small">Lokasi belum diperoleh</div>
                                        </div>
                                    </div>

                                    <div id="gpsInfo" class="small text-muted mb-3 d-none">
                                        <div>Latitude: <span id="gpsLatitude">-</span></div>
                                        <div>Longitude: <span id="gpsLongitude">-</span></div>
                                        <div>Akurasi GPS: <span id="gpsAccuracy">-</span> meter</div>
                                    </div>

                                    <div id="gpsErrorAlert" class="alert alert-danger d-none mb-3" role="alert">
                                        GPS wajib diaktifkan untuk melakukan Presensi Hadir.
                                    </div>

                                    <div id="cameraAlert" class="alert alert-info d-none mb-3" role="alert">
                                        <i class="bi bi-camera-video"></i> <strong>📷 Kamera Aktif</strong> - Pastikan izinkan akses kamera untuk selfie.
                                    </div>

                                    <div id="cameraPanel" class="card shadow-sm border-0 rounded-4 mb-3 d-none">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-2 mb-3 text-success">
                                                <i class="bi bi-camera-fill fs-5"></i>
                                                <span class="fw-semibold">Kamera Aktif</span>
                                            </div>
                                            <div class="ratio ratio-4x3 rounded-4 overflow-hidden bg-dark">
                                                <video id="cameraPreview" class="w-100 h-100" autoplay playsinline muted></video>
                                            </div>
                                            <small class="text-muted mt-2 d-block">Preview kamera akan muncul di atas. Tekan Ambil Foto saat siap.</small>
                                        </div>
                                    </div>

                                    <div id="photoPreviewPanel" class="card shadow-sm border-0 rounded-4 mb-3 d-none">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-2 mb-3 text-secondary">
                                                <i class="bi bi-camera-reels fs-5"></i>
                                                <span class="fw-semibold">Preview Selfie</span>
                                            </div>
                                            <div class="ratio ratio-4x3 rounded-4 overflow-hidden bg-secondary">
                                                <img id="capturedPhotoPreview" src="" alt="Preview Selfie" class="w-100 h-100 object-fit-cover">
                                            </div>
                                            <small class="text-muted mt-2 d-block">Jika sudah cocok, tekan Gunakan Foto untuk mengunci selfie.</small>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-info rounded-4 h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-3">
                                    <span class="badge bg-info text-dark">Izin</span>
                                </div>
                                <h5 class="card-title">Presensi Izin</h5>
                                <p class="card-text text-muted">Ajukan izin dengan keterangan dan bukti pendukung.</p>
                                <button id="toggleIzinBtn" class="btn btn-outline-info w-100 mt-auto" type="button" data-bs-toggle="collapse" data-bs-target="#izinForm">Pilih Izin</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning rounded-4 h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-3">
                                    <span class="badge bg-warning text-dark">Sakit</span>
                                </div>
                                <h5 class="card-title">Presensi Sakit</h5>
                                <p class="card-text text-muted">Laporkan sakit dengan keterangan dan bukti medis.</p>
                                <button id="toggleSakitBtn" class="btn btn-outline-warning w-100 mt-auto" type="button" data-bs-toggle="collapse" data-bs-target="#sakitForm">Pilih Sakit</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="collapse @if(old('jenis_presensi') === 'izin') show @endif" id="izinForm">
                    <div class="card shadow-sm border-0 rounded-4 mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Form Presensi Izin</h5>
                            <form action="{{ route('pegawai.presensi.checkin') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="jenis_presensi" value="izin">
                                <div class="mb-3">
                                    <label for="keterangan_izin" class="form-label">Keterangan</label>
                                    <textarea id="keterangan_izin" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="bukti_file_izin" class="form-label">Upload Bukti</label>
                                    <input type="file" id="bukti_file_izin" name="bukti_file" class="form-control @error('bukti_file') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                                    @error('bukti_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-info text-white">Kirim Izin</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="collapse @if(old('jenis_presensi') === 'sakit') show @endif" id="sakitForm">
                    <div class="card shadow-sm border-0 rounded-4 mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Form Presensi Sakit</h5>
                            <form action="{{ route('pegawai.presensi.checkin') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="jenis_presensi" value="sakit">
                                <div class="mb-3">
                                    <label for="keterangan_sakit" class="form-label">Keterangan Sakit</label>
                                    <textarea id="keterangan_sakit" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="bukti_file_sakit" class="form-label">Upload Bukti</label>
                                    <input type="file" id="bukti_file_sakit" name="bukti_file" class="form-control @error('bukti_file') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                                    @error('bukti_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-warning">Kirim Sakit</button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <h5 class="card-title">Detail Presensi Hari Ini</h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted">Status</span>
                                        <div class="fs-5 fw-semibold">{{ $todayAttendance->status_kehadiran?->label() ?? ucfirst($todayAttendance->status_kehadiran) }}</div>
                                    </div>
                                    @if($todayAttendance->bukti_file)
                                        <a href="{{ asset('storage/' . $todayAttendance->bukti_file) }}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat Bukti</a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted">Jam Masuk</span>
                                <div class="fs-5 fw-semibold">{{ $todayAttendance->jam_masuk ? date('H:i:s', strtotime($todayAttendance->jam_masuk)) : '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted">Jam Keluar</span>
                                <div class="fs-5 fw-semibold">{{ $todayAttendance->jam_keluar ? date('H:i:s', strtotime($todayAttendance->jam_keluar)) : '-' }}</div>
                            </div>
                            <div class="col-12">
                                <span class="text-muted">Keterangan</span>
                                <div class="fs-5 fw-semibold">{{ $todayAttendance->keterangan ?? '-' }}</div>
                            </div>
                            <div class="col-12">
                                @if($statusToday === 'hadir' && !$todayAttendance->jam_keluar)
                                    <form action="{{ route('pegawai.presensi.checkout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning w-100">Presensi Pulang</button>
                                    </form>
                                @elseif($statusToday === 'hadir' && $todayAttendance->jam_keluar)
                                    <div class="alert alert-success mb-0">Presensi hari ini selesai.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card shadow-sm border-0 rounded-4 mt-3">
                <div class="card-body">
                    <h5 class="card-title">Riwayat 10 Presensi Terakhir</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Keterangan</th>
                                    <th>Bukti</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history as $attendance)
                                    <tr>
                                        <td>{{ $attendance->tanggal->format('Y-m-d') }}</td>
                                        <td>{{ $attendance->status_kehadiran?->label() ?? ucfirst($attendance->status_kehadiran ?? '-') }}</td>
                                        <td>{{ $attendance->jam_masuk ? date('H:i:s', strtotime($attendance->jam_masuk)) : '-' }}</td>
                                        <td>{{ $attendance->jam_keluar ? date('H:i:s', strtotime($attendance->jam_keluar)) : '-' }}</td>
                                        <td>{{ $attendance->keterangan ?? '-' }}</td>
                                        <td>
                                            @if($attendance->bukti_file)
                                                <a href="{{ asset('storage/' . $attendance->bukti_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat Bukti</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada riwayat presensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const openCameraBtn = document.getElementById('openCameraBtn');
    const capturePhotoBtn = document.getElementById('capturePhotoBtn');
    const retakePhotoBtn = document.getElementById('retakePhotoBtn');
    const usePhotoBtn = document.getElementById('usePhotoBtn');
    const submitHadirBtn = document.getElementById('submitHadirBtn');
    const cameraPanel = document.getElementById('cameraPanel');
    const cameraAlert = document.getElementById('cameraAlert');
    const photoPreviewPanel = document.getElementById('photoPreviewPanel');
    const cameraPreview = document.getElementById('cameraPreview');
    const capturedPhotoPreview = document.getElementById('capturedPhotoPreview');
    const fotoSelfieInput = document.getElementById('foto_selfie');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const gpsStatus = document.getElementById('gpsStatus');
    const gpsStatusText = document.getElementById('gpsStatusText');
    const gpsInfo = document.getElementById('gpsInfo');
    const gpsLatitude = document.getElementById('gpsLatitude');
    const gpsLongitude = document.getElementById('gpsLongitude');
    const gpsAccuracy = document.getElementById('gpsAccuracy');
    const gpsErrorAlert = document.getElementById('gpsErrorAlert');

    let mediaStream = null;
    let latestPhotoData = '';
    let locationAcquired = false;
    let photoCaptured = false;

    function show(element, visible) {
        if (!element) return;
        element.classList.toggle('d-none', !visible);
    }

    function enable(button, enabled) {
        if (!button) return;
        button.disabled = !enabled;
    }

    function stopCamera() {
        if (!mediaStream) return;
        mediaStream.getTracks().forEach(track => track.stop());
        mediaStream = null;
        if (cameraPreview) {
            cameraPreview.srcObject = null;
        }
    }

    function setGpsStatus(success) {
        if (!gpsStatus || !gpsStatusText) return;
        gpsStatus.classList.toggle('alert-danger', !success);
        gpsStatus.classList.toggle('alert-success', success);
        gpsStatusText.textContent = success ? 'Lokasi berhasil diperoleh' : 'Lokasi belum diperoleh';

        if (!success) {
            show(gpsInfo, false);
            if (gpsLatitude) gpsLatitude.textContent = '-';
            if (gpsLongitude) gpsLongitude.textContent = '-';
            if (gpsAccuracy) gpsAccuracy.textContent = '-';
        }
    }

    function showGpsError(message) {
        if (!gpsErrorAlert) return;
        gpsErrorAlert.textContent = message;
        show(gpsErrorAlert, true);
    }

    function updateSubmitState() {
        const shouldEnable = locationAcquired && photoCaptured;
        enable(submitHadirBtn, shouldEnable);
    }

    function resetPreview() {
        latestPhotoData = '';
        photoCaptured = false;
        if (capturedPhotoPreview) {
            capturedPhotoPreview.src = '';
        }
        if (fotoSelfieInput) {
            fotoSelfieInput.value = '';
        }
        show(photoPreviewPanel, false);
        show(usePhotoBtn, false);
        show(retakePhotoBtn, false);
        show(gpsErrorAlert, false);
        setGpsStatus(false);
        locationAcquired = false;
        if (latitudeInput) latitudeInput.value = '';
        if (longitudeInput) longitudeInput.value = '';
        updateSubmitState();
    }

    function requestLocation() {
        if (!navigator.geolocation) {
            showGpsError('GPS tidak tersedia di browser Anda.');
            setGpsStatus(false);
            return;
        }

        show(gpsErrorAlert, false);
        setGpsStatus(false);

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const { latitude, longitude, accuracy } = position.coords;
                locationAcquired = true;
                if (latitudeInput) latitudeInput.value = latitude;
                if (longitudeInput) longitudeInput.value = longitude;
                if (gpsLatitude) gpsLatitude.textContent = latitude.toFixed(7);
                if (gpsLongitude) gpsLongitude.textContent = longitude.toFixed(7);
                if (gpsAccuracy) gpsAccuracy.textContent = accuracy.toFixed(1);
                show(gpsInfo, true);
                setGpsStatus(true);
                updateSubmitState();
            },
            (error) => {
                locationAcquired = false;
                setGpsStatus(false);
                showGpsError('GPS wajib diaktifkan untuk melakukan Presensi Hadir.');
                updateSubmitState();
            },
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0,
            }
        );
    }

    function openCamera() {
        requestLocation();

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Browser Anda tidak mendukung akses kamera.');
            return;
        }

        const constraints = {
            video: { facingMode: { exact: 'user' } },
            audio: false,
        };

        navigator.mediaDevices.getUserMedia(constraints)
            .catch(() => navigator.mediaDevices.getUserMedia({ video: true, audio: false }))
            .then(stream => {
                mediaStream = stream;
                if (cameraPreview) {
                    cameraPreview.srcObject = stream;
                    cameraPreview.play().catch(() => {});
                }
                show(cameraPanel, true);
                show(cameraAlert, true);
                enable(capturePhotoBtn, true);
                enable(openCameraBtn, false);
            })
            .catch(() => {
                alert('Tidak dapat mengakses kamera. Periksa izin kamera dan coba lagi.');
            });
    }

    function capturePhoto() {
        if (!mediaStream || !cameraPreview || !cameraPreview.videoWidth || !cameraPreview.videoHeight) {
            return;
        }

        const canvas = document.createElement('canvas');
        canvas.width = cameraPreview.videoWidth;
        canvas.height = cameraPreview.videoHeight;
        const context = canvas.getContext('2d');
        context.drawImage(cameraPreview, 0, 0, canvas.width, canvas.height);
        latestPhotoData = canvas.toDataURL('image/jpeg', 0.92);

        if (capturedPhotoPreview) {
            capturedPhotoPreview.src = latestPhotoData;
        }

        show(photoPreviewPanel, true);
        show(usePhotoBtn, true);
        show(retakePhotoBtn, true);
        enable(capturePhotoBtn, false);
    }

    function usePhoto() {
        if (!latestPhotoData || !fotoSelfieInput) {
            return;
        }

        fotoSelfieInput.value = latestPhotoData;
        photoCaptured = true;
        show(cameraPanel, false);
        show(cameraAlert, false);
        updateSubmitState();
        enable(openCameraBtn, false);
        enable(capturePhotoBtn, false);
    }

    function retakePhoto() {
        resetPreview();
        if (mediaStream) {
            show(cameraPanel, true);
            show(cameraAlert, true);
            enable(capturePhotoBtn, true);
            enable(openCameraBtn, false);
        } else {
            openCamera();
        }
    }

    if (openCameraBtn) {
        openCameraBtn.addEventListener('click', function () {
            resetPreview();
            openCamera();
        });
    }

    if (capturePhotoBtn) {
        capturePhotoBtn.addEventListener('click', function () {
            capturePhoto();
        });
    }

    if (usePhotoBtn) {
        usePhotoBtn.addEventListener('click', function () {
            usePhoto();
            stopCamera();
        });
    }

    if (retakePhotoBtn) {
        retakePhotoBtn.addEventListener('click', function () {
            retakePhoto();
        });
    }

    // Bootstrap Collapse Setup for Izin and Sakit Forms
    // With data-bs-toggle and data-bs-target attributes on buttons, this serves as a fallback
    const toggleIzinBtn = document.getElementById('toggleIzinBtn');
    const toggleSakitBtn = document.getElementById('toggleSakitBtn');
    const izinEl = document.getElementById('izinForm');
    const sakitEl = document.getElementById('sakitForm');

    // Initialize Bootstrap Collapse instances
    let izinCollapse = null;
    let sakitCollapse = null;

    try {
        if (typeof bootstrap !== 'undefined') {
            // Create instances without toggling initially
            if (izinEl) {
                izinCollapse = bootstrap.Collapse.getOrCreateInstance(izinEl, { toggle: false });
            }
            if (sakitEl) {
                sakitCollapse = bootstrap.Collapse.getOrCreateInstance(sakitEl, { toggle: false });
            }

            // Add additional safety: prevent form from closing when clicking inside it
            if (izinEl) {
                izinEl.addEventListener('click', function (e) {
                    // Allow click events to propagate normally within the form
                    // Don't prevent default or stop propagation
                });
            }

            if (sakitEl) {
                sakitEl.addEventListener('click', function (e) {
                    // Allow click events to propagate normally within the form
                    // Don't prevent default or stop propagation
                });
            }

            // Ensure forms stay open when submitting
            const forms = document.querySelectorAll('[name="jenis_presensi"]');
            forms.forEach(input => {
                const form = input.closest('form');
                if (form) {
                    form.addEventListener('submit', function (e) {
                        // Allow form to submit naturally
                        // Do NOT prevent default submission
                    });
                }
            });

            console.log('[Presensi] Bootstrap collapse initialized successfully');
        }
    } catch (err) {
        console.warn('[Presensi] Collapse init warning:', err);
    }

    // Log when user interacts with collapse buttons
    if (toggleIzinBtn) {
        toggleIzinBtn.addEventListener('click', function () {
            console.log('[Presensi] Toggle Izin button clicked');
        });
    }

    if (toggleSakitBtn) {
        toggleSakitBtn.addEventListener('click', function () {
            console.log('[Presensi] Toggle Sakit button clicked');
        });
    }


    window.addEventListener('beforeunload', stopCamera);
});
</script>
@endpush
