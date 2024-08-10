<?php
// Obtener el usuario_id desde la URL
if (isset($_GET['usuario_id'])) {
  $usuario_id = $_GET['usuario_id'];
} else {
  // Manejar el caso donde no se proporciona un usuario_id válido
  die("Error: No se ha proporcionado un ID de usuario válido.");
}
?>


<div class="container-inscripcion">
  <h2 style="color:white;">Mostrar Inscripciones</h2>
  <div id="inscripcion-container" class="inscripcion-container">
    <!-- Aquí se cargarán dinámicamente los archivos -->
  </div>
</div>

