<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Reportes de calidad del agua</title>
	@vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
	<header class="border-b border-slate-200 bg-white">
		<div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-5 sm:px-6 lg:px-8">
			<a href="{{ route('dashboard') }}" class="text-sm font-semibold text-slate-700 hover:text-teal-700">
				Calidad del agua
			</a>
		</div>
	</header>

	<main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
		<div class="mb-8 flex flex-wrap items-end justify-between gap-4">
			<div>
				<p class="text-sm font-semibold uppercase tracking-wide text-teal-800">Seguimiento</p>
				<h1 class="mt-2 text-3xl font-semibold text-slate-950">Reportes</h1>
				<p class="mt-2 text-sm text-slate-600">Consulta los reportes de calidad del agua que has registrado.</p>
			</div>
			<div class="flex items-center gap-4">
				<p class="text-sm text-slate-600">
					<span class="font-semibold text-slate-900">{{ $reportes->count() }}</span>
					{{ $reportes->count() === 1 ? 'reporte' : 'reportes' }}
				</p>
				<a href="{{ route('reportes.create') }}" style="background-color: #0f766e; color: #fff;" class="inline-flex items-center gap-2 rounded-md bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2">
					<span aria-hidden="true">+</span>
					Crear reporte
				</a>
			</div>
		</div>

		@if (session('success'))
			<div role="status" class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
				{{ session('success') }}
			</div>
		@endif

		<section aria-label="Lista de reportes" class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
			@if ($reportes->isEmpty())
				<div class="px-6 py-16 text-center">
					<h2 class="text-lg font-semibold text-slate-900">Aún no tienes reportes</h2>
					<p class="mt-2 text-sm text-slate-600">Cuando registres uno, aparecerá aquí.</p>
				</div>
			@else
				<div class="overflow-x-auto">
					<table class="min-w-full divide-y divide-slate-200 text-left text-sm">
						<caption class="sr-only">Reportes de calidad del agua</caption>
						<thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-600">
							<tr>
								<th scope="col" class="px-5 py-3.5">Folio</th>
								<th scope="col" class="px-5 py-3.5">Usuario</th>
								<th scope="col" class="px-5 py-3.5">Ubicación</th>
								<th scope="col" class="px-5 py-3.5">Descripción</th>
								<th scope="col" class="whitespace-nowrap px-5 py-3.5">Fecha de inicio</th>
								<th scope="col" class="whitespace-nowrap px-5 py-3.5">Fecha de fin</th>
								<th scope="col" class="px-5 py-3.5 text-right">Acciones</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-slate-100">
							@foreach ($reportes as $reporte)
								<tr class="align-top hover:bg-slate-50">
									<td class="whitespace-nowrap px-5 py-4 font-medium text-slate-900">
										{{ $reporte->folio ?: 'Sin folio' }}
									</td>
									<td class="whitespace-nowrap px-5 py-4 text-slate-700">
										{{ $reporte->user?->name ?? 'Usuario no disponible' }}
									</td>
									<td class="min-w-48 px-5 py-4 text-slate-700">{{ $reporte->ubicacion }}</td>
									<td class="max-w-md px-5 py-4 text-slate-600" title="{{ $reporte->descripcion }}">
										{{ \Illuminate\Support\Str::limit($reporte->descripcion ?: 'Sin descripción', 120) }}
									</td>
									<td class="whitespace-nowrap px-5 py-4 text-slate-600">
										{{ \Illuminate\Support\Carbon::parse($reporte->fecha_inicio)->format('d/m/Y') }}
									</td>
									<td class="whitespace-nowrap px-5 py-4 text-slate-600">
										{{ \Illuminate\Support\Carbon::parse($reporte->fecha_fin)->format('d/m/Y') }}
									</td>
									<td class="whitespace-nowrap px-5 py-4 text-right">
										<div class="flex justify-end gap-2">
											<a href="{{ route('reportes.show', $reporte) }}" class="text-sm font-medium text-teal-700 hover:text-teal-900">Ver</a>
											<a href="{{ route('reportes.edit', $reporte) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">Editar</a>
											<form action="{{ route('reportes.destroy', $reporte) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar este reporte?');">
												@csrf
												@method('DELETE')
												<button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Eliminar</button>
											</form>
										</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@endif
		</section>
	</main>
</body>
</html>
