<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del reporte</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <main class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:py-16">
        <a href="{{ route('reportes.index') }}" class="text-sm font-semibold text-teal-800 hover:text-teal-900">&larr; Volver a reportes</a>

        <div class="mt-6 rounded-lg border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <p class="text-sm font-semibold uppercase tracking-wide text-teal-800">Detalle</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950">Reporte {{ $reporte->folio ?: 'sin folio' }}</h1>

            <dl class="mt-8 space-y-5 text-sm text-slate-700">
                <div>
                    <dt class="font-semibold text-slate-900">Usuario</dt>
                    <dd>{{ $reporte->user?->name ?? 'Usuario no disponible' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-900">Ubicación</dt>
                    <dd>{{ $reporte->ubicacion }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-900">Descripción</dt>
                    <dd class="whitespace-pre-line">{{ $reporte->descripcion ?: 'Sin descripción' }}</dd>
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <dt class="font-semibold text-slate-900">Fecha de inicio</dt>
                        <dd>{{ \Illuminate\Support\Carbon::parse($reporte->fecha_inicio)->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-900">Fecha de fin</dt>
                        <dd>{{ \Illuminate\Support\Carbon::parse($reporte->fecha_fin)->format('d/m/Y') }}</dd>
                    </div>
                </div>
            </dl>
        </div>
    </main>
</body>
</html>
