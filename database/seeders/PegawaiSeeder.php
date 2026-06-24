<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $divisiMap = Divisi::pluck('id', 'nama_divisi')->all();
        $jabatanMap = Jabatan::pluck('id', 'nama_jabatan')->all();

        $employees = [
            [
                'nip' => '198501012019032001',
                'nama' => 'Aria Pratama',
                'jenis_kelamin' => 'male',
                'alamat' => 'Jalan Melati No. 15, Jakarta Selatan',
                'no_hp' => '081234567890',
                'tanggal_masuk' => '2019-03-01',
                'status' => 'active',
                'divisi' => 'Teknologi Informasi',
                'jabatan' => 'Manajer',
            ],
            [
                'nip' => '199002152020012002',
                'nama' => 'Nadia Putri',
                'jenis_kelamin' => 'female',
                'alamat' => 'Jalan Kenanga No. 21, Bandung',
                'no_hp' => '081298765432',
                'tanggal_masuk' => '2020-01-15',
                'status' => 'active',
                'divisi' => 'Sumber Daya Manusia',
                'jabatan' => 'Supervisor',
            ],
            [
                'nip' => '198809102018072003',
                'nama' => 'Rizky Hidayat',
                'jenis_kelamin' => 'male',
                'alamat' => 'Jalan Wijaya Kusuma No. 45, Surabaya',
                'no_hp' => '081257843210',
                'tanggal_masuk' => '2018-07-10',
                'status' => 'active',
                'divisi' => 'Operasional',
                'jabatan' => 'Staff',
            ],
            [
                'nip' => '199312042021052004',
                'nama' => 'Maya Sari',
                'jenis_kelamin' => 'female',
                'alamat' => 'Jalan Cempaka No. 8, Yogyakarta',
                'no_hp' => '081266778899',
                'tanggal_masuk' => '2021-05-02',
                'status' => 'active',
                'divisi' => 'Keuangan',
                'jabatan' => 'Staff',
            ],
            [
                'nip' => '199507272019112005',
                'nama' => 'Dewi Anggraeni',
                'jenis_kelamin' => 'female',
                'alamat' => 'Perumahan Griya Asri Blok B 12, Bekasi',
                'no_hp' => '081345678901',
                'tanggal_masuk' => '2019-11-27',
                'status' => 'active',
                'divisi' => 'Pemasaran',
                'jabatan' => 'Supervisor',
            ],
            [
                'nip' => '198707132017092006',
                'nama' => 'Bambang Setiawan',
                'jenis_kelamin' => 'male',
                'alamat' => 'Jalan Diponegoro No. 33, Semarang',
                'no_hp' => '081298765431',
                'tanggal_masuk' => '2017-09-13',
                'status' => 'active',
                'divisi' => 'Operasional',
                'jabatan' => 'Manajer',
            ],
            [
                'nip' => '199011082020052007',
                'nama' => 'Intan Permata',
                'jenis_kelamin' => 'female',
                'alamat' => 'Jalan Gajah Mada No. 10, Medan',
                'no_hp' => '081211223344',
                'tanggal_masuk' => '2020-05-08',
                'status' => 'active',
                'divisi' => 'Teknologi Informasi',
                'jabatan' => 'Staff',
            ],
            [
                'nip' => '198604022016062008',
                'nama' => 'Fajar Nugroho',
                'jenis_kelamin' => 'male',
                'alamat' => 'Jalan Merdeka No. 22, Malang',
                'no_hp' => '081233344455',
                'tanggal_masuk' => '2016-06-02',
                'status' => 'active',
                'divisi' => 'Keuangan',
                'jabatan' => 'Analis',
            ],
            [
                'nip' => '199306182018102009',
                'nama' => 'Siti Khadijah',
                'jenis_kelamin' => 'female',
                'alamat' => 'Jalan Melur No. 9, Malang',
                'no_hp' => '081277788899',
                'tanggal_masuk' => '2018-10-18',
                'status' => 'active',
                'divisi' => 'Sumber Daya Manusia',
                'jabatan' => 'Staff',
            ],
            [
                'nip' => '199812302022012010',
                'nama' => 'Galih Prasetya',
                'jenis_kelamin' => 'male',
                'alamat' => 'Jalan Slamet Riyadi No. 5, Solo',
                'no_hp' => '081299988877',
                'tanggal_masuk' => '2022-01-30',
                'status' => 'active',
                'divisi' => 'Pemasaran',
                'jabatan' => 'Staff',
            ],
            [
                'nip' => '199205242019122011',
                'nama' => 'Rini Wulandari',
                'jenis_kelamin' => 'female',
                'alamat' => 'Jalan Sawo No. 14, Padang',
                'no_hp' => '081222334455',
                'tanggal_masuk' => '2019-12-24',
                'status' => 'active',
                'divisi' => 'Pemasaran',
                'jabatan' => 'Analis',
            ],
            [
                'nip' => '198912182017032012',
                'nama' => 'Hendra Wijaya',
                'jenis_kelamin' => 'male',
                'alamat' => 'Jalan Veteran No. 18, Makassar',
                'no_hp' => '081255566677',
                'tanggal_masuk' => '2017-03-18',
                'status' => 'inactive',
                'divisi' => 'Operasional',
                'jabatan' => 'Staff',
            ],
            [
                'nip' => '199501132021082013',
                'nama' => 'Nina Amalia',
                'jenis_kelamin' => 'female',
                'alamat' => 'Jalan Sultan Agung No. 2, Banten',
                'no_hp' => '081244556677',
                'tanggal_masuk' => '2021-08-13',
                'status' => 'active',
                'divisi' => 'Sumber Daya Manusia',
                'jabatan' => 'Analis',
            ],
            [
                'nip' => '199707032023012014',
                'nama' => 'Dian Pratiwi',
                'jenis_kelamin' => 'female',
                'alamat' => 'Jalan Bunga No. 7, Tangerang',
                'no_hp' => '081233445566',
                'tanggal_masuk' => '2023-01-03',
                'status' => 'active',
                'divisi' => 'Teknologi Informasi',
                'jabatan' => 'Staff',
            ],
            [
                'nip' => '199108242019062015',
                'nama' => 'Ridwan Kurniawan',
                'jenis_kelamin' => 'male',
                'alamat' => 'Jalan Veteran No. 27, Palembang',
                'no_hp' => '081266554433',
                'tanggal_masuk' => '2019-06-24',
                'status' => 'active',
                'divisi' => 'Operasional',
                'jabatan' => 'Supervisor',
            ],
            [
                'nip' => '198911132020102016',
                'nama' => 'Andini Saputri',
                'jenis_kelamin' => 'female',
                'alamat' => 'Jalan Mawar No. 19, Bandung',
                'no_hp' => '081277799900',
                'tanggal_masuk' => '2020-10-13',
                'status' => 'inactive',
                'divisi' => 'Keuangan',
                'jabatan' => 'Staff',
            ],
        ];

        foreach ($employees as $employee) {
            Pegawai::updateOrCreate(
                ['nip' => $employee['nip']],
                [
                    'nama' => $employee['nama'],
                    'jenis_kelamin' => $employee['jenis_kelamin'],
                    'alamat' => $employee['alamat'],
                    'no_hp' => $employee['no_hp'],
                    'tanggal_masuk' => $employee['tanggal_masuk'],
                    'status' => $employee['status'],
                    'divisi_id' => $divisiMap[$employee['divisi']] ?? null,
                    'jabatan_id' => $jabatanMap[$employee['jabatan']] ?? null,
                ]
            );
        }
    }
}
