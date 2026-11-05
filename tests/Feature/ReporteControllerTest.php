<?php

use App\Models\Reportes;
use App\Models\User;

test('usuario puede ver listado de sus reportes', function () {
    // Dado un usuario
    $user = User::factory()->create();
    $reportes = Reportes::factory()
        ->recycle($user)
        ->count(3)
        ->create();
        // ->create(['user_id' => $user->id]);

    // Cuando el usuario accede a la ruta de reportes
    $response = $this->actingAs($user)->get('/reportes');

    // Entonces debería ver la página de reportes con el título "Reportes"
    $response->assertStatus(200)
        ->assertSee('Reportes')
        ->assertSee($reportes->first()->descripcion);
});

test('usuario puede ver formulario para crear un nuevo reporte', function () {
    // Dado un usuario
    $user = User::factory()->create();

    // Cuando el usuario accede a la ruta de creación de reportes
    $response = $this->actingAs($user)->get(route('reportes.create'));

    // Entonces debería ver la página de creación de reportes con el título "Crear Reporte"
    $response->assertStatus(200)
        ->assertSee('Crea tu reporte de agua');
});