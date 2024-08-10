<?php
/**
 * ..controller/class_fechas.php
 *
 * @version 1.0
 * @author Pablo Alexander Mondragon Acevedo
*/

class Fecha {
    public static function mostrarFechas($fecha) {
        date_default_timezone_set('America/Bogota');
        $fechaInicial = new DateTime($fecha);//Se crea en un onjeto la fecha de creacion
        $fechaActual = new DateTime();//Se crea en un onjeto la fecha de actual
        
        // Obtener la diferencia entre las dos fechas
        $intervalo = $fechaActual->diff($fechaInicial);

        // Calcular la diferencia en años, meses, semanas, días, horas, minutos y segundos
        $años = $intervalo->y;
        $meses = $intervalo->m;
        $semanas = floor($intervalo->days / 7); // Convertir días a semanas
        $dias = $intervalo->days % 7;
        $horas = $intervalo->h;
        $minutos = $intervalo->i;
        $segundos = $intervalo->s;

        // Determinar el tiempo transcurrido
        if ($años > 0) {
            $salida = "hace: " . $años . " año" . ($años > 1 ? "s" : "");
        } elseif ($meses > 0) {
            $salida = "hace: " . $meses . " mes" . ($meses > 1 ? "es" : "");
        } elseif ($semanas > 0) {
            $salida = "hace: " . $semanas . " semana" . ($semanas > 1 ? "s" : "");
        } elseif ($dias > 0) {
            $salida = "hace: " . $dias . " día" . ($dias > 1 ? "s" : "");
        } elseif ($horas > 0) {
            $salida = "hace: " . $horas . " hora" . ($horas > 1 ? "s" : "");
        } elseif ($minutos > 0) {
            $salida = "hace: " . $minutos . " minuto" . ($minutos > 1 ? "s" : "");
        } else {
            $salida = "hace: " . $segundos . " segundo" . ($segundos > 1 ? "s" : "");
        }

        return $salida;
    }
}

