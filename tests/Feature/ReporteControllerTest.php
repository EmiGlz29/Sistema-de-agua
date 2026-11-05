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

test('usuario puede crear un nuevo reporte', function(){
    //usuario
    $response = $this->actingAs(User::factory()->create())->post(route('reportes.store'), [
        'ubicacion' => 'Colonia Centro',
        'descripcion' => 'Reporte de calidad del agua',
        'fecha_inicio' => '2026-10-01',
        'fecha_fin' => '2026-10-20',
        'folio' => 'REP-001',
    ]);

    $response->assertRedirect(route('reportes.index'));
    $this->assertDatabaseHas('reportes', [
        'ubicacion' => 'Colonia Centro',
        'descripcion' => 'Reporte de calidad del agua',
        'fecha_inicio' => '2026-10-01',
        'fecha_fin' => '2026-10-20',
        'folio' => 'REP-001',
    ]);
});

test('formulario de reporte valida la ubicación y el orden de las fechas', function () {
    // Dado un usuario y el total actual de reportes
    $user = User::factory()->create();
    $reportesAntes = Reportes::count();

    // Cuando envía una ubicación vacía y una fecha de fin anterior a la de inicio
    $response = $this->actingAs($user)->post(route('reportes.store'), [
        'ubicacion' => '',
        'descripcion' => 'Reporte inválido',
        'fecha_inicio' => '2026-10-01',
        'fecha_fin' => '2026-09-30',
        'folio' => 'REP-INVALIDO',
    ]);

    // Entonces recibe errores de validación y la base de datos no cambia
    $response->assertSessionHasErrors([
        'ubicacion',
        'fecha_fin',
    ]);
    $this->assertDatabaseCount('reportes', $reportesAntes);
});

test('usuario puede ver un reporte específico', function () {
    // Dado un usuario y un reporte
    $user = User::factory()->create();
    $reporte = Reportes::factory()->recycle($user)->create();

    // Cuando el usuario accede a la ruta de detalle del reporte
    $response = $this->actingAs($user)->get(route('reportes.show', $reporte->id));

    // Entonces debería ver la página de detalle del reporte con la descripción del reporte
    $response->assertStatus(200)
        ->assertSee($reporte->descripcion);
});

test('usuario puede ver el formulario para editar un reporte', function () {
    // Dado un usuario y un reporte
    $user = User::factory()->create();
    $reporte = Reportes::factory()->recycle($user)->create([
        'ubicacion' => 'Colonia Centro',
    ]);

    // Cuando el usuario accede al formulario de edición
    $response = $this->actingAs($user)->get(route('reportes.edit', $reporte->id));

    // Entonces debería ver el formulario con los datos del reporte
    $response->assertStatus(200)
        ->assertSee('Editar reporte')
        ->assertSee('Colonia Centro');
});

test('usuario puede actualizar un reporte', function () {
    // Dado un usuario y un reporte
    $user = User::factory()->create();
    $reporte = Reportes::factory()->recycle($user)->create();

    // Cuando el usuario actualiza el reporte
    $response = $this->actingAs($user)->put(route('reportes.update', $reporte->id), [
        'ubicacion' => 'Colonia Centro',
        'descripcion' => 'Reporte actualizado de calidad del agua',
        'fecha_inicio' => '2026-10-01',
        'fecha_fin' => '2026-10-20',
        'folio' => 'REP-002',
    ]);

    // Entonces debería volver al listado y guardar los cambios
    $response->assertRedirect(route('reportes.index'));
    $this->assertDatabaseHas('reportes', [
        'id' => $reporte->id,
        'ubicacion' => 'Colonia Centro',
        'descripcion' => 'Reporte actualizado de calidad del agua',
        'fecha_inicio' => '2026-10-01',
        'fecha_fin' => '2026-10-20',
        'folio' => 'REP-002',
    ]);
});

test('usuario puede eliminar un reporte', function () {
    // Dado un usuario y un reporte
    $user = User::factory()->create();
    $reporte = Reportes::factory()->recycle($user)->create();

    // Cuando el usuario elimina el reporte
    $response = $this->actingAs($user)->delete(route('reportes.destroy', $reporte->id));

    // Entonces debería volver al listado y eliminar el reporte
    $response->assertRedirect(route('reportes.index'));
    $this->assertDatabaseMissing('reportes', [
        'id' => $reporte->id,
    ]);
});

test('usuario no puede ver, editar, actualizar o eliminar reportes de otros usuarios', function () {
    // Dado dos usuarios y un reporte de uno de ellos
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $reporte = Reportes::factory()->recycle($otherUser)->create();

    // Cuando el primer usuario intenta acceder al reporte del segundo usuario
    $responseShow = $this->actingAs($user)->get(route('reportes.show', $reporte->id));
    $responseEdit = $this->actingAs($user)->get(route('reportes.edit', $reporte->id));
    $responseUpdate = $this->actingAs($user)->put(route('reportes.update', $reporte->id), [
        'ubicacion' => 'Colonia Centro',
        'descripcion' => 'Intento de actualización',
        'fecha_inicio' => '2026-10-01',
        'fecha_fin' => '2026-10-20',
        'folio' => 'REP-003',
    ]);
    $responseDelete = $this->actingAs($user)->delete(route('reportes.destroy', $reporte->id));

    // Entonces debería recibir un error 403 (Forbidden) en todos los intentos
    $responseShow->assertStatus(403);
    $responseEdit->assertStatus(403);
    $responseUpdate->assertStatus(403);
    $responseDelete->assertStatus(403);
});
