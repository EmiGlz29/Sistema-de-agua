<?php

namespace Database\Factories;

use App\Models\Reportes;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends Factory<Reportes>
 */
class ReportesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fechaInicio = fake()->date();

        return [
            'usuario_id' => User::factory(),
            'ubicacion' => fake()->address(),
            'descripcion' => fake()->paragraph(),
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => fake()->dateTimeBetween($fechaInicio, '+1 year')->format('Y-m-d'),
            'folio' => fake()->optional()->bothify('REP-####'),
        ];
    }
}
