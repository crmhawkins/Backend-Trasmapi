@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Anuncios</h1>
    <a href="{{ route('anuncios.create') }}" class="btn btn-primary mb-4">➕ Crear nuevo anuncio</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @foreach ($anuncios as $anuncio)
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">{{ $anuncio->titulo }}</h5>
                <p class="card-text">{{ $anuncio->texto }}</p>
                <p><strong>Tipo:</strong> {{ ucfirst($anuncio->tipo) }}</p>
                @if($anuncio->link)
                    <p><strong>Link:</strong> <a href="{{ $anuncio->link }}" target="_blank">{{ $anuncio->link }}</a></p>
                @endif

                @if ($anuncio->tipo === 'imagen')
                    <img src="{{ asset('storage/' . $anuncio->media) }}" alt="Anuncio" class="img-fluid rounded mb-3" style="max-height: 300px;">
                @elseif ($anuncio->tipo === 'video')
                    <video src="{{ asset('storage/' . $anuncio->media) }}" controls class="img-fluid rounded mb-3" style="max-height: 300px;"></video>
                @endif

                <div class="d-flex justify-content-between">
                    <a href="{{ route('anuncios.edit', $anuncio->id) }}" class="btn btn-sm btn-warning">✏️ Editar</a>
                    <form action="{{ route('anuncios.destroy', $anuncio->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este anuncio?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">🗑️ Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <div class="d-flex justify-content-center">
        {{ $anuncios->links() }}
    </div>
</div>
@endsection
