<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\DimDivisi;
use App\Models\DimJabatan;
use App\Models\DimPegawai;
use App\Models\DimWaktu;
use App\Models\FactAbsensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WarehouseEtlService
{
    public function loadWarehouse(): void
    {
        DB::transaction(function () {
            $this->loadDimensions();
            $this->loadFacts();
        });
    }

    private function loadDimensions(): void
    {
        $absensi = Absensi::with(['pegawai.divisi', 'pegawai.jabatan'])->get();

        $pegawaiRows = [];
        $divisiRows = [];
        $jabatanRows = [];
        $waktuRows = [];

        foreach ($absensi as $item) {
            $pegawai = $item->pegawai;
            if ($pegawai) {
                $pegawaiRows[$pegawai->id] = [
                    'pegawai_id' => $pegawai->id,
                    'nip' => $pegawai->nip,
                    'nama' => $pegawai->nama,
                    'jenis_kelamin' => $pegawai->jenis_kelamin,
                    'status' => $pegawai->status,
                    'tanggal_masuk' => $pegawai->tanggal_masuk?->format('Y-m-d'),
                ];

                if ($pegawai->divisi) {
                    $divisiRows[$pegawai->divisi->id] = [
                        'divisi_id' => $pegawai->divisi->id,
                        'nama_divisi' => $pegawai->divisi->nama_divisi,
                    ];
                }

                if ($pegawai->jabatan) {
                    $jabatanRows[$pegawai->jabatan->id] = [
                        'jabatan_id' => $pegawai->jabatan->id,
                        'nama_jabatan' => $pegawai->jabatan->nama_jabatan,
                    ];
                }
            }

            if ($item->tanggal) {
                $tanggal = $item->tanggal->format('Y-m-d');
                $date = Carbon::createFromFormat('Y-m-d', $tanggal);

                $waktuRows[$tanggal] = [
                    'tanggal' => $tanggal,
                    'hari' => $date->day,
                    'bulan' => $date->month,
                    'tahun' => $date->year,
                    'nama_hari' => $date->locale('id')->dayName,
                    'nama_bulan' => $date->locale('id')->monthName,
                ];
            }
        }

        foreach ($divisiRows as $divisi) {
            DimDivisi::updateOrCreate(
                ['divisi_id' => $divisi['divisi_id']],
                ['nama_divisi' => $divisi['nama_divisi']]
            );
        }

        foreach ($jabatanRows as $jabatan) {
            DimJabatan::updateOrCreate(
                ['jabatan_id' => $jabatan['jabatan_id']],
                ['nama_jabatan' => $jabatan['nama_jabatan']]
            );
        }

        foreach ($pegawaiRows as $pegawai) {
            DimPegawai::updateOrCreate(
                ['pegawai_id' => $pegawai['pegawai_id']],
                [
                    'nip' => $pegawai['nip'],
                    'nama' => $pegawai['nama'],
                    'jenis_kelamin' => $pegawai['jenis_kelamin'],
                    'status' => $pegawai['status'],
                    'tanggal_masuk' => $pegawai['tanggal_masuk'],
                ]
            );
        }

        foreach ($waktuRows as $waktu) {
            DimWaktu::updateOrCreate(
                ['tanggal' => $waktu['tanggal']],
                [
                    'hari' => $waktu['hari'],
                    'bulan' => $waktu['bulan'],
                    'tahun' => $waktu['tahun'],
                    'nama_hari' => $waktu['nama_hari'],
                    'nama_bulan' => $waktu['nama_bulan'],
                ]
            );
        }
    }

    private function loadFacts(): void
    {
        $absensi = Absensi::with(['pegawai.divisi', 'pegawai.jabatan'])->get();
        $factGroups = [];

        foreach ($absensi as $record) {
            $pegawai = $record->pegawai;
            if (! $pegawai || ! $record->tanggal) {
                continue;
            }

            $key = $pegawai->id . '|' . $record->tanggal->format('Y-m-d');
            if (! isset($factGroups[$key])) {
                $factGroups[$key] = [
                    'pegawai' => $pegawai,
                    'tanggal' => $record->tanggal->format('Y-m-d'),
                    'total_hadir' => 0,
                    'total_telat' => 0,
                    'total_izin' => 0,
                    'total_sakit' => 0,
                    'total_alfa' => 0,
                ];
            }

            $status = is_object($record->status_kehadiran) ? $record->status_kehadiran->value : $record->status_kehadiran;
            $late = is_object($record->status_keterlambatan) ? $record->status_keterlambatan->value : $record->status_keterlambatan;

            if ($status === 'hadir') {
                $factGroups[$key]['total_hadir']++;
            }
            if ($status === 'izin') {
                $factGroups[$key]['total_izin']++;
            }
            if ($status === 'sakit') {
                $factGroups[$key]['total_sakit']++;
            }
            if ($status === 'alpa') {
                $factGroups[$key]['total_alfa']++;
            }
            if ($late === 'late') {
                $factGroups[$key]['total_telat']++;
            }
        }

        $pegawaiKeyCache = DimPegawai::pluck('pegawai_key', 'pegawai_id')->all();
        $divisiKeyCache = DimDivisi::pluck('divisi_key', 'divisi_id')->all();
        $jabatanKeyCache = DimJabatan::pluck('jabatan_key', 'jabatan_id')->all();
        $waktuKeyCache = DimWaktu::pluck('waktu_key', 'tanggal')->all();

        foreach ($factGroups as $group) {
            $pegawaiId = $group['pegawai']->id;
            $divisiId = $group['pegawai']->divisi?->id;
            $jabatanId = $group['pegawai']->jabatan?->id;
            $tanggal = $group['tanggal'];

            $pegawaiKey = $pegawaiKeyCache[$pegawaiId] ?? null;
            $divisiKey = $divisiId ? ($divisiKeyCache[$divisiId] ?? null) : null;
            $jabatanKey = $jabatanId ? ($jabatanKeyCache[$jabatanId] ?? null) : null;
            $waktuKey = $waktuKeyCache[$tanggal] ?? null;

            if (! $pegawaiKey || ! $waktuKey) {
                continue;
            }

            FactAbsensi::updateOrCreate(
                ['pegawai_key' => $pegawaiKey, 'waktu_key' => $waktuKey],
                [
                    'divisi_key' => $divisiKey,
                    'jabatan_key' => $jabatanKey,
                    'total_hadir' => $group['total_hadir'],
                    'total_telat' => $group['total_telat'],
                    'total_izin' => $group['total_izin'],
                    'total_sakit' => $group['total_sakit'],
                    'total_alfa' => $group['total_alfa'],
                ]
            );
        }
    }
}
