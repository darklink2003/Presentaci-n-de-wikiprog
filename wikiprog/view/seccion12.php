<?php
// Obtener el usuario_id desde la URL
if (isset($_GET['usuario_id'])) {
    $usuario_id = $_GET['usuario_id'];
} else {
    // Manejar el caso donde no se proporciona un usuario_id válido
    die("Error: No se ha proporcionado un ID de usuario válido.");
}
?>

<style>
    .archivo-container {
        margin-bottom: 1px;
        /* Espaciado entre contenedores */
        padding: 10px;
        /* Ajusta el relleno según sea necesario */
        color: white;
        /* Asegúrate de que el texto sea legible sobre el fondo oscuro */
        border-radius: 5px 5px 5px 5px;
    }

    .archivo-container:last-child {
        margin-bottom: 0;
        /* Elimina el margen inferior del último contenedor */

    }

    .bg-custom-dark {
        background-color: #232230;
    }

    .bg-custom-darker {
        background-color: #121113;
    }

    .form-control-file {
        color: white;
        
    }
</style>

<div class="container-fluid bg-custom-dark text-white py-4" style="border-radius: 0.9375rem;">
    <div class="container">
        <div class="row gy-3">
            <!-- Primera columna con enlaces -->
            <div class="col-md-3">
                <div class="bg-custom-darker p-3 rounded-3 h-100">
                    <h2>Subir Archivos</h2>
                    <form action="../model/guardar_archivo.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="archivo" class="form-label text-white">Selecciona un archivo</label>
                            <input type="file" class="form-control form-control-file" id="archivo" name="archivo">
                        </div>
                        <div class="mb-3">
                            <label for="privacidad" class="form-label text-white">Privacidad</label>
                            <select class="form-select" id="privacidad" name="privacidad">
                                <option value="1">Pública</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Subir Archivo</button>
                        <input type="hidden" name="usuario_id" value="<?php echo htmlspecialchars($usuario_id); ?>">
                    </form>
                </div>
            </div>
            <!-- Segunda columna con formulario de subida de archivos y contenedor de archivos -->
            <div class="col-md-9">

                <div class="bg-custom-darker p-3 rounded-3">
                    <div class="container-archivos">
                        <h2>Mostrar Archivos</h2>
                        <div id="archivos-container" class="archivo-container">
                            <!-- Aquí se cargarán dinámicamente los archivos -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
