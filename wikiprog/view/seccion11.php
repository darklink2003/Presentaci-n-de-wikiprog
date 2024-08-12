<?php
// Incluir el archivo de configuración de la base de datos
include ('../model/db_config.php');

// Obtener las fechas de inicio y fin desde el formulario
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Consulta SQL para obtener inscripciones por curso y mes, filtrando por el rango de fechas
$query = "
    SELECT c.titulo_curso, DATE_FORMAT(i.fecha_registro, '%Y-%m') as mes, COUNT(i.inscripción_id) as inscriptos 
    FROM curso c 
    LEFT JOIN inscripción i ON c.curso_id = i.curso_id 
    WHERE (i.fecha_registro >= '$fecha_inicio' AND i.fecha_registro <= '$fecha_fin') OR (i.fecha_registro IS NULL)
    GROUP BY c.curso_id, c.titulo_curso, mes
    ORDER BY c.titulo_curso, mes
";

$result = $conn->query($query);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[$row['titulo_curso']][$row['mes']] = $row['inscriptos'];
    }
} else {
    echo "No se encontraron resultados.";
}
$conn->close();

// Preparar los datos para Chart.js
$labels = [];
$datasets = [];
$colors = [
    'rgba(255, 99, 132, 1)',
    'rgba(75, 99, 132, 1)',
    'rgba(255, 206, 86, 1)',
    'rgba(75, 192, 192, 1 )',
    'rgba(153, 102, 255, 1)',
    'rgba(255, 159, 64, 1)',
    'rgba(255, 99, 71, 1)',
    'rgba(0, 255, 0, 1)',
    'rgba(0, 191, 255, 1)',
    'rgba(255, 165, 0, 1)'
];
$borderColors = [
    'rgba(255, 99, 132, 1)',
    'rgba(54, 162, 235, 1)',
    'rgba(255, 206, 86, 1)',
    'rgba(75, 192, 192, 1)',
    'rgba(153, 102, 255, 1)',
    'rgba(255, 159, 64, 1)',
    'rgba(255, 99, 71, 1)',
    'rgba(0, 255, 0, 1)',
    'rgba(0, 191, 255, 1)',
    'rgba(255, 165, 0, 1)'
];

foreach ($data as $curso => $meses) {
    $dataset = [
        'label' => $curso,
        'data' => [],
        'backgroundColor' => $colors[count($datasets) % count($colors)],
        'borderColor' => $borderColors[count($datasets) % count($borderColors)],
        'borderWidth' => 1
    ];
    foreach ($meses as $mes => $inscriptos) {
        if (!in_array($mes, $labels)) {
            $labels[] = $mes;
        }
        $dataset['data'][] = $inscriptos;
    }
    // Completar los meses que faltan en los datos de cada curso
    while (count($dataset['data']) < count($labels)) {
        $dataset['data'][] = 0;
    }
    $datasets[] = $dataset;
}

// Convertir datos a JSON
$labels_json = json_encode($labels);
$datasets_json = json_encode($datasets);
?>

<div class="container mt-5">
    <h2 class="text-center">Estadísticas de Inscripciones en Cursos</h2>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Formulario para seleccionar el rango de fechas -->
            <form method="GET" class="mb-3">
                <div class="form-group">
                    <label for="fecha_inicio">Fecha de inicio:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?php echo $fecha_inicio; ?>" required>
                </div>
                <div class="form-group">
                    <label for="fecha_fin">Fecha de fin:</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="<?php echo $fecha_fin; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Filtrar</button>
            </form>

            <!-- Gráfico de Chart.js -->
            <canvas id="myChart" width="50" height="25" style="background-color:white; padding: 10px;"></canvas>
        </div>
    </div>
</div>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var labels = <?php echo $labels_json; ?>;
    var datasets = <?php echo $datasets_json; ?>;

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
