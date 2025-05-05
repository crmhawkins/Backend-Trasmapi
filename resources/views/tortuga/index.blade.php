@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Puntuaciones de Tortugas</h2>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="from_date" class="form-label">Desde</label>
            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
        </div>
        <div class="col-md-4">
            <label for="to_date" class="form-label">Hasta</label>
            <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    <div class="mb-3">
        <h5>Total de puntuaciones: <span class="badge bg-success">{{ $totalPuntuacion }}</span></h5>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Puntuación</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posidonias as $p)
                    <tr>
                        <td>{{ $p->nombre }}</td>
                        <td>{{ $p->puntuacion }}</td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No hay datos para mostrar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $posidonias->withQueryString()->links() }}
</div>
@endsection
