<?php

namespace App\Http\Controllers;

use App\Models\reportes;
use Illuminate\Http\Request;

class ReportesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('reportes/listar-reportes', [
            'reportes' => reportes::query()
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

        $reporte = new reportes;
        $reporte->usuario_id = auth()->user()->id;
        $reporte->Ubicacion = $validated['ubicacion'];
        $reporte->Descripcion = $validated['descripcion'] ?? '';
        $reporte->Fecha_inicio = $validated['fecha_inicio'];
        $reporte->Fecha_fin = $validated['fecha_fin'];
        $reporte->Folio = $validated['folio'] ?? null;
        $reporte->save();

        return redirect()
            ->route('reportes.index')
            ->with('success', 'El reporte se guardó correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(reportes $reportes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(reportes $reportes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, reportes $reportes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(reportes $reportes)
    {
        //
    }
}
