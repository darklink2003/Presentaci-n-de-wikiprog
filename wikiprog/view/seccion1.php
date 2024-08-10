<!-- seccion1.php -->
<div class="container">
    <!-- Barra de búsqueda -->
    <div class="row">
        <div class="col">
            <form action="#" method="GET" class="d-flex" id="form-busqueda">
                <input type="search" class="form-control me-2" name="q" placeholder="Buscar...">
                <button type="submit" class="btn btn-dark">Buscar</button>
            </form>
        </div>
    </div>

    <!-- Contenedor de cursos -->
    <div class="row mt-3">
        <div id="cursos-container"></div>
        <div id="indicador-carga" style="display: none;">Cargando...</div>
        <div id="error-container" style="color: red;"></div>
    </div>
</div>

<!-- Plantilla para los cursos -->
<script type="text/template" id="curso-template">
    <div class="curso" data-curso-id="{curso_id}">
        <h2 class="titulo-curso"></h2>
        <p class="descripcion-curso"></p>
        <a class="ver-lecciones-link" href="#">Ver lecciones</a>
        <div class="reacciones">
            <span class="like-count">Aprovado: 0</span>
            <span class="dislike-count">Desaprovado: 0</span>
        </div>
    </div>
</script>
