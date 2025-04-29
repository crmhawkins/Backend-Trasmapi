@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Anuncios</h1>
    <a href="{{ route('anuncios.create') }}" class="btn btn-primary mb-3">Crear nuevo</a>

    @foreach ($anuncios as $anuncio)
        <div class="card mb-2 p-3">
            <h5>{{ $anuncio->titulo }}</h5>
            <p>{{ $anuncio->texto }}</p>
            <p><strong>Tipo:</strong> {{ $anuncio->tipo }}</p>
            <p><strong>Link:</strong> <a href="{{ $anuncio->link }}" target="_blank">{{ $anuncio->link }}</a></p>
            <a href="{{ route('anuncios.edit', $anuncio->id) }}" class="btn btn-sm btn-warning">Editar</a>
        </div>
    @endforeach

    {{ $anuncios->links() }}
</div>
@endsection
