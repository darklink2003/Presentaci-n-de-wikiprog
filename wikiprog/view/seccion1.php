<!-- seccion1.php -->
<div class="container">
    <!-- Barra de búsqueda -->
    <div class="row mb-4">
        <div class="col">
            <form action="#" method="GET" class="d-flex" id="form-busqueda" aria-labelledby="search-form">
                <input type="search" class="form-control me-2" name="q" placeholder="Buscar..." aria-label="Buscar">
                <button type="submit" class="btn btn-dark" aria-label="Buscar">Buscar</button>
            </form>
        </div>
    </div>

    <!-- Contenedor de cursos -->
    <div class="row">
        <div id="cursos-container" class="d-flex flex-wrap gap-3"></div>
        <div id="indicador-carga" class="text-center" style="display: none;">Cargando...</div>
        <div id="error-container" class="text-danger"></div>
    </div>
</div>

<!-- Plantilla para los cursos -->
<script type="text/template" id="curso-template">
    <div class="curso" data-curso-id="{curso_id}">
        <h2 class="titulo-curso"></h2>
        <p class="descripcion-curso"></p>
        <a class="ver-lecciones-link btn btn-primary" href="#" role="button" aria-label="Ver lecciones">Ver lecciones</a>
        <div class="reacciones">
            <span class="like-count">Aprobado: 0</span>
            <span class="dislike-count">Desaprobado: 0</span>
        </div>
    </div>
</script>
