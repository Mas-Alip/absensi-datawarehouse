<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AbsensiSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $pegawaiList = Pegawai::all();
        $today = Carbon::today();
        $startDate = $today->copy()->subMonths(4);

        if ($pegawaiList->isEmpty()) {
            $this->command->warn('Tidak ada pegawai untuk absensi. Jalankan PegawaiSeeder terlebih dahulu.');
            return;
        }

        foreach ($pegawaiList as $pegawai) {
            $recordsToCreate = rand(10, 25);
            $dates = collect();

            while ($dates->count() < $recordsToCreate) {
                $dates->push($faker->dateTimeBetween($startDate, $today)->format('Y-m-d'));
            }

            $dates = $dates->unique()->values();

            foreach ($dates as $tanggal) {
                $statusKehadiran = $this->pickAttendanceStatus($faker);
                $jamMasuk = null;
                $jamKeluar = null;

                if ($statusKehadiran === 'hadir') {
                    $onTime = $faker->boolean(60);
                    $jamMasuk = $onTime
                        ? $faker->dateTimeBetween("{$tanggal} 06:30:00", "{$tanggal} 07:00:00")->format('H:i')
                        : $faker->dateTimeBetween("{$tanggal} 07:01:00", "{$tanggal} 09:00:00")->format('H:i');
                    $jamKeluar = $faker->dateTimeBetween("{$tanggal} 16:00:00", "{$tanggal} 18:30:00")->format('H:i');
                }

                Absensi::updateOrCreate(
                    [
                        'pegawai_id' => $pegawai->id,
                        'tanggal' => $tanggal,
                    ],
                    [
                        'jam_masuk' => $jamMasuk,
                        'jam_keluar' => $jamKeluar,
                        'status_kehadiran' => $statusKehadiran,
                        'status_keterlambatan' => $this->computeLateStatus($jamMasuk),
                        'keterangan' => $statusKehadiran !== 'hadir' ? $this->buildReason($statusKehadiran, $faker) : $faker->sentence(4),
                    ]
                );
            }
        }
    }

    private function pickAttendanceStatus($faker): string
    {
        $roll = $faker->numberBetween(1, 100);

        return match (true) {
            $roll <= 70 => 'hadir',
            $roll <= 80 => 'izin',
            $roll <= 90 => 'sakit',
            default => 'alpa',
        };
    }

    private function computeLateStatus(?string $jamMasuk): string
    {
        if (! $jamMasuk) {
            return 'on_time';
        }

        $time = Carbon::createFromFormat('H:i', $jamMasuk);
        $threshold = Carbon::createFromTimeString('07:00');

        return $time->greaterThan($threshold) ? 'late' : 'on_time';
    }

    private function buildReason(string $status, $faker): ?string
    {
        return match ($status) {
            'izin' => $faker->randomElement(['Survey lokasi proyek', 'Keluarga sakit', 'Rapat penting', 'Urusan pribadi']),
            'sakit' => $faker->randomElement(['Demam', 'Sakit kepala', 'Batuk pilek', 'Nyeri otot']),
            'alpa' => $faker->randomElement(['Tidak masuk tanpa keterangan', 'Absen mendadak', 'Terlambat dan pulang', 'Tidak hadir']),
            default => null,
        };
    }
}
