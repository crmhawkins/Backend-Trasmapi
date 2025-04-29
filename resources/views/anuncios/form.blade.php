<div class="mb-3">
    <label>Título</label>
    <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $anuncio->titulo ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Texto</label>
    <textarea name="texto" class="form-control">{{ old('texto', $anuncio->texto ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label>Tipo</label>
    <select name="tipo" class="form-control" required>
        <option value="imagen" @selected(old('tipo', $anuncio->tipo ?? '') == 'imagen')>Imagen</option>
        <option value="video" @selected(old('tipo', $anuncio->tipo ?? '') == 'video')>Video</option>
    </select>
</div>

<div class="mb-3">
    <label>Media (Imagen o Video)</label>
    <input type="file" name="media" class="form-control" @if(!isset($anuncio)) required @endif>
    @if(isset($anuncio))
        <p class="mt-2">Archivo actual: {{ basename($anuncio->media) }}</p>
    @endif
</div>

<div class="mb-3">
    <label>Link externo (opcional)</label>
    <input type="url" name="link" class="form-control" value="{{ old('link', $anuncio->link ?? '') }}">
</div>

<button type="submit" class="btn btn-success">Guardar</button>
