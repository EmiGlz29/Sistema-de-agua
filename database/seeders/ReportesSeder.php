<?php

namespace Database\Seeders;

use App\Models\Reportes;
use Illuminate\Database\Seeder;

class ReportesSeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reportes::create([
            'usuario_id' => 1,
            'ubicacion' => 'Casa k',
            'descripcion' => '1234',
            'fecha_inicio' => '2026-05-05',
            'fecha_fin' => '2026-05-10',
            'folio' => 'SEED-001',
        ]);
    }
}
