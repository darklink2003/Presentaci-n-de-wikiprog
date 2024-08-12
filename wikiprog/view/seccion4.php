<!-- seccion4.php -->

<!-- Formulario para la creación y edición de cursos -->
<div class="contenedor_descripcion">
    <form action="../model/guardar_curso.php" method="POST" enctype="multipart/form-data" onsubmit="return validarFormularioCurso();">
        <!-- Encabezado del formulario -->
        <div class="row" style="height:100px;background-color: #1a1924; border-radius:15px;">
            <!-- Columna izquierda para título y descripción -->
            <div class="col-md-8 d-flex justify-content-center align-items-center">
                <input type="text" name="titulo_curso" placeholder="Título del curso" required class="form-control"
                    style="border-radius: 10px;">
                <input type="text" name="descripcion" placeholder="Descripción" class="form-control"
                    style="margin-left:10px; border-radius: 10px;" required>
            </div>
            <!-- Columna derecha para selección de categoría -->
            <div class="col-md-4 d-flex justify-content-center align-items-center">
                <select name="categoria" required class="form-select"
                    style="background-color: #1a1924; color: white; border-radius: 10px;">
                    <?php
                    // Conexión a la base de datos
                    include '../model/db_config.php';

                    // Obtener las categorías
                    $sql = "SELECT categoria_id, descripcion FROM categoria";
                    $resultado = $conn->query($sql);

                    if ($resultado->num_rows > 0) {
                        while ($row = $resultado->fetch_assoc()) {
                            echo '<option value="' . $row['categoria_id'] . '">' . $row['descripcion'] . '</option>';
                        }
                    }

                    // Cerrar la conexión
                    $conn->close();
                    ?>
                </select>
            </div>
        </div>

        <br>
        <!-- Contenedor de secciones de lecciones y pruebas -->
        <div class="contenedor_secciones">
            <!-- Sección de lecciones -->
            <div class="seccion-lecciones">
                <h3 style="color: white;">Lecciones</h3>
                <div id="lecciones">
                    <!-- Aquí se agregarán las lecciones dinámicamente -->
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" onclick="agregarLeccion()" class="btn btn-primary"
                            style="margin-top: 10px; border-radius: 10px;">Agregar otra lección</button>
                    </div>
                </div>
            </div>

            <!-- Sección de pruebas -->
            <div class="seccion-pruebas">
                <h3 style="color: white;">Pruebas</h3>
                <div id="pruebas">
                    <!-- Aquí se agregarán las pruebas dinámicamente -->
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" onclick="agregarPrueba()" class="btn btn-primary"
                            style="margin-top: 10px; border-radius: 10px;">Agregar otra prueba</button>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <!-- Botón de envío del formulario -->
        <input type="submit" value="Enviar" class="btn btn-success">
    </form>
</div>

<!-- Script JavaScript para agregar dinámicamente campos de prueba -->
<script>
    function validarFormularioCurso() {
        const lecciones = document.querySelectorAll('#lecciones .col-md-12');
        const pruebas = document.querySelectorAll('#pruebas .prueba');

        if (lecciones.length === 0) {
            alert('Debes agregar al menos una lección.');
            return false;
        }

        if (pruebas.length === 0) {
            alert('Debes agregar al menos una prueba.');
            return false;
        }

        return true;
    }

    var numPruebas = 0;

    function agregarPrueba() {
        numPruebas++;

        var divPrueba = document.createElement("div");
        divPrueba.setAttribute("class", "prueba");
        divPrueba.setAttribute("style", "margin-top: 10px; background-color:#292835; padding:20px; border-radius:15px");

        var tituloInput = document.createElement("input");
        tituloInput.setAttribute("type", "text");
        tituloInput.setAttribute("name", "titulo_prueba[]");
        tituloInput.setAttribute("placeholder", "Título de la prueba");
        tituloInput.setAttribute("class", "form-control");
        tituloInput.setAttribute("style", "border-radius: 10px;");

        var contenidoTextarea = document.createElement("textarea");
        contenidoTextarea.setAttribute("name", "contenido_prueba[]");
        contenidoTextarea.setAttribute("placeholder", "Contenido de la prueba");
        contenidoTextarea.setAttribute("class", "form-control");
        contenidoTextarea.setAttribute("rows", "3");
        contenidoTextarea.setAttribute("style", "margin-top: 10px; border-radius: 10px;");

        var archivoInput = document.createElement("input");
        archivoInput.setAttribute("type", "file");
        archivoInput.setAttribute("name", "archivo_prueba[]");
        archivoInput.setAttribute("class", "form-control");
        archivoInput.setAttribute("style", "margin-top: 10px; border-radius: 10px;");

        var eliminarBtn = document.createElement("button");
        eliminarBtn.setAttribute("type", "button");
        eliminarBtn.setAttribute("class", "btn btn-danger");
        eliminarBtn.setAttribute("style", "margin-top: 10px; border-radius: 10px;");
        eliminarBtn.textContent = "Eliminar prueba";
        eliminarBtn.onclick = function() {
            divPrueba.remove();
        };

        divPrueba.appendChild(tituloInput);
        divPrueba.appendChild(contenidoTextarea);
        divPrueba.appendChild(archivoInput);
        divPrueba.appendChild(eliminarBtn);

        document.getElementById("pruebas").appendChild(divPrueba);
    }

    function agregarLeccion() {
        const leccionesContainer = document.getElementById("lecciones");
        const leccionHTML = `
            <div class="col-md-12 mt-3">
                <div class="bg-dark p-3 rounded">
                    <div class="form-group mb-3">
                        <label for="titulo_leccion" class="form-label">Título de la lección</label>
                        <input type="text" class="form-control" name="titulo_leccion[]" placeholder="Título de la lección" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="contenido_leccion" class="form-label">Descripción</label>
                        <textarea class="form-control" name="contenido_leccion[]" placeholder="Descripción" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="archivo_leccion" class="form-label">Archivo de la lección</label>
                        <input type="file" class="form-control" name="archivo_leccion[]" required>
                    </div>
                    <button type="button" class="btn btn-danger" onclick="eliminarLeccion(this)">Eliminar lección</button>
                </div>
            </div>
        `;
        const leccionDiv = document.createElement("div");
        leccionDiv.innerHTML = leccionHTML.trim();
        leccionesContainer.appendChild(leccionDiv.firstChild);
    }

    function eliminarLeccion(button) {
        document.getElementById("lecciones").removeChild(button.closest(".col-md-12"));
    }
</script>
