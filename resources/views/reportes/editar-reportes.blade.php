<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar reporte</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <main class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:py-16">
        <a href="{{ route('reportes.index') }}" class="text-sm font-semibold text-teal-800 hover:text-teal-900">&larr; Volver a reportes</a>

        <div class="mt-6 rounded-lg border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <p class="text-sm font-semibold uppercase tracking-wide text-teal-800">Actualización</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950">Editar reporte</h1>

            @if ($errors->any())
                <ul class="mt-5 list-disc rounded-md border border-red-200 bg-red-50 px-4 py-3 pl-9 text-sm text-red-800">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form action="{{ route('reportes.update', $reporte) }}" method="POST" class="mt-8 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="ubicacion" class="block text-sm font-medium text-slate-800">Ubicación del reporte</label>
                    <textarea name="ubicacion" id="ubicacion" rows="2" class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" required>{{ old('ubicacion', $reporte->ubicacion) }}</textarea>

                    <label for="descripcion" class="mt-5 block text-sm font-medium text-slate-800">Descripción del reporte</label>
                    <textarea name="descripcion" id="descripcion" rows="4" class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20">{{ old('descripcion', $reporte->descripcion) }}</textarea>

                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="fecha_inicio" class="block text-sm font-medium text-slate-800">Fecha de inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" value="{{ old('fecha_inicio', $reporte->fecha_inicio) }}" required>
                        </div>
                        <div>
                            <label for="fecha_fin" class="block text-sm font-medium text-slate-800">Fecha de fin</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" value="{{ old('fecha_fin', $reporte->fecha_fin) }}" required>
                        </div>
                    </div>

                    <label for="folio" class="mt-5 block text-sm font-medium text-slate-800">Folio <span class="font-normal text-slate-500">(opcional)</span></label>
                    <input type="text" name="folio" id="folio" class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" value="{{ old('folio', $reporte->folio) }}">
                </div>

                <button type="submit" class="inline-flex rounded-md bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2">Actualizar reporte</button>
            </form>
        </div>
    </main>
</body>
</html>
