<?php

namespace App\Http\Controllers;

use App\Models\Reportes;
use Illuminate\Http\Request;

class ReportesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('reportes/listar-reportes', [
            'reportes' => Reportes::query()
                ->with('user')
                ->where('usuario_id', auth()->id())
                ->latest()
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reportes/crear-reportes');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ubicacion' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'folio' => ['nullable', 'string', 'max:255'],
        ]);

        Reportes::create([
            'usuario_id' => auth()->id(),
            'ubicacion' => $validated['ubicacion'],
            'descripcion' => $validated['descripcion'] ?? '',
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'],
            'folio' => $validated['folio'] ?? null,
        ]);

        return redirect()
            ->route('reportes.index')
            ->with('success', 'El reporte se guardó correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reportes $reporte)
    {
        if ((int) $reporte->usuario_id !== (int) auth()->id()) {
            abort(403);
        }

        return view('reportes/mostrar-reportes', ['reporte' => $reporte]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reportes $reporte)
    {
        if ((int) $reporte->usuario_id !== (int) auth()->id()) {
            abort(403);
        }

        return view('reportes/editar-reportes', ['reporte' => $reporte]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reportes $reporte)
    {
        if ((int) $reporte->usuario_id !== (int) auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'ubicacion' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'folio' => ['nullable', 'string', 'max:255'],
        ]);

        $reporte->fill([
            'ubicacion' => $validated['ubicacion'],
            'descripcion' => $validated['descripcion'] ?? '',
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'],
            'folio' => $validated['folio'] ?? null,
        ]);

        $reporte->save();

        return redirect()
            ->route('reportes.index')
            ->with('success', 'El reporte se actualizó correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reportes $reporte)
    {
        if ((int) $reporte->usuario_id !== (int) auth()->id()) {
            abort(403);
        }

        $reporte->delete();

        return redirect()
            ->route('reportes.index')
            ->with('success', 'El reporte se eliminó correctamente.');
    }
}
