<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $divisions = [
            'Sumber Daya Manusia',
            'Keuangan',
            'Operasional',
            'Teknologi Informasi',
            'Pemasaran',
        ];

        foreach ($divisions as $name) {
            Divisi::updateOrCreate([
                'nama_divisi' => $name,
            ]);
        }
    }
}
