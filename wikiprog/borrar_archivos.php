<?php
// borrar_archivos.php

$files_to_delete = ['temp.sql']; // Lista de archivos temporales a borrar

foreach ($files_to_delete as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "Archivo $file borrado con éxito.<br>";
    } else {
        echo "Archivo $file no encontrado.<br>";
    }
}
?>
