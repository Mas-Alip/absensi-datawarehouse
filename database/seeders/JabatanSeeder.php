<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $positions = [
            'Manajer',
            'Supervisor',
            'Staff',
            'Analis',
        ];

        foreach ($positions as $name) {
            Jabatan::updateOrCreate([
                'nama_jabatan' => $name,
            ]);
        }
    }
}
