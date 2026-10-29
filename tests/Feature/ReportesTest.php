<?php

use App\Models\User;
use App\Models\Reportes;

test('report factory creates a report with a related user and valid dates', function () {
    $reporte = Reportes::factory()->create();

    expect($reporte->user)->toBeInstanceOf(User::class)
        ->and($reporte->fecha_fin)->toBeGreaterThanOrEqual($reporte->fecha_inicio);
});

test('authenticated users can open the report creation form using its short URL', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/crear-reportes');

    $response->assertRedirect(route('reportes.create'));

    $this->actingAs($user)
        ->get(route('reportes.create'))
        ->assertOk()
        ->assertSee('Crea tu reporte de agua');
});

test('authenticated users can create a report with all report fields', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('reportes.store'), [
        'ubicacion' => 'Colonia Centro',
        'descripcion' => 'Reporte de calidad del agua',
        'fecha_inicio' => '2026-10-01',
        'fecha_fin' => '2026-10-20',
        'folio' => 'REP-001',
    ]);

    $response->assertRedirect(route('reportes.index'));
    $this->assertDatabaseHas('reportes', [
        'usuario_id' => $user->id,
        'Ubicacion' => 'Colonia Centro',
        'Descripcion' => 'Reporte de calidad del agua',
        'Fecha_inicio' => '2026-10-01',
        'Fecha_fin' => '2026-10-20',
        'Folio' => 'REP-001',
    ]);
});

test('authenticated users only see their own reports in the listing', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $this->actingAs($user)->post(route('reportes.store'), [
        'ubicacion' => 'Colonia Centro',
        'descripcion' => 'Reporte propio',
        'fecha_inicio' => '2026-10-01',
        'fecha_fin' => '2026-10-20',
        'folio' => 'REP-PROPIO',
    ]);

    $this->actingAs($otherUser)->post(route('reportes.store'), [
        'ubicacion' => 'Colonia Norte',
        'descripcion' => 'Reporte de otra persona',
        'fecha_inicio' => '2026-10-02',
        'fecha_fin' => '2026-10-21',
        'folio' => 'REP-AJENO',
    ]);

    $response = $this->actingAs($user)->get(route('reportes.index'));

    $response->assertOk()
        ->assertSee('REP-PROPIO')
        ->assertSee($user->name)
        ->assertSee('Colonia Centro')
        ->assertDontSee('REP-AJENO')
        ->assertDontSee('Colonia Norte');
});

test('report owners can view, edit, update, and delete their reports', function () {
    $user = User::factory()->create();
    $reporte = Reportes::factory()->for($user, 'user')->create();

    expect((int) $reporte->usuario_id)->toBe((int) $user->id);

    $this->actingAs($user)
        ->get(route('reportes.show', $reporte))
        ->assertOk();

    $this->actingAs($user)
        ->get(route('reportes.edit', $reporte))
        ->assertOk();

    $this->actingAs($user)
        ->put(route('reportes.update', $reporte), [
            'ubicacion' => 'Colonia Actualizada',
            'descripcion' => 'Descripción actualizada',
            'fecha_inicio' => '2026-10-01',
            'fecha_fin' => '2026-10-20',
            'folio' => 'REP-ACTUALIZADO',
        ])
        ->assertRedirect(route('reportes.index'));

    $this->assertDatabaseHas('reportes', [
        'id' => $reporte->id,
        'ubicacion' => 'Colonia Actualizada',
    ]);

    $this->actingAs($user)
        ->delete(route('reportes.destroy', $reporte))
        ->assertRedirect(route('reportes.index'));

    $this->assertDatabaseMissing('reportes', ['id' => $reporte->id]);
});

test('users cannot view, edit, update, or delete another users report', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $reporte = Reportes::factory()->for($owner, 'user')->create();

    $this->actingAs($otherUser)
        ->get(route('reportes.show', $reporte))
        ->assertForbidden();

    $this->actingAs($otherUser)
        ->get(route('reportes.edit', $reporte))
        ->assertForbidden();

    $this->actingAs($otherUser)
        ->put(route('reportes.update', $reporte), [
            'ubicacion' => 'Ubicación no autorizada',
            'descripcion' => '',
            'fecha_inicio' => '2026-10-01',
            'fecha_fin' => '2026-10-20',
            'folio' => null,
        ])
        ->assertForbidden();

    $this->actingAs($otherUser)
        ->delete(route('reportes.destroy', $reporte))
        ->assertForbidden();

    $this->assertDatabaseHas('reportes', [
        'id' => $reporte->id,
        'usuario_id' => $owner->id,
    ]);
});
