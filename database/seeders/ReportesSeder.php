<?php

namespace Database\Seeders;

use App\Models\Reportes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportesSeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reportes::create([
            'user_id' => 1,
            'ubicacion' => 'Casa k',
            'descripcion'    => '1234',
            'fecha_inicio' => '2026-05-05'
        ]);
    }
}
