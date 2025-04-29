@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar anuncio</h1>
    <form action="{{ route('anuncios.update', $anuncio->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('anuncios.form', ['anuncio' => $anuncio])
    </form>
</div>
@endsection
