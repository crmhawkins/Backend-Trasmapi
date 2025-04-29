@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear anuncio</h1>
    <form action="{{ route('anuncios.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('anuncios.form')
    </form>
</div>
@endsection
