<?php
// Incluir el archivo de configuración de la base de datos
include ('../model/db_config.php');

// Consulta SQL para obtener inscripciones por curso y mes
$query = "
    SELECT c.titulo_curso, DATE_FORMAT(i.fecha_registro, '%Y-%m') as mes, COUNT(i.inscripción_id) as inscriptos 
    FROM curso c 
    LEFT JOIN inscripción i ON c.curso_id = i.curso_id 
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

// Agregar meses únicos a las etiquetas
foreach ($data as $curso => $meses) {
    foreach ($meses as $mes => $inscriptos) {
        if (!in_array($mes, $labels)) {
            $labels[] = $mes;
        }
    }
}

// Ordenar las etiquetas
sort($labels);

foreach ($data as $curso => $meses) {
    $dataset = [
        'label' => $curso,
        'data' => [],
        'backgroundColor' => $colors[count($datasets) % count($colors)],
        'borderColor' => $borderColors[count($datasets) % count($borderColors)],
        'borderWidth' => 2
    ];
    foreach ($labels as $mes) {
        $dataset['data'][] = isset($meses[$mes]) ? $meses[$mes] : 0;
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
            <canvas id="myChart" width="600" height="400" style="background-color:white; padding: 10px;"></canvas>
        </div>
    </div>
</div>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var labels = <?php echo $labels_json; ?>;
    var datasets = <?php echo $datasets_json; ?>;

    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            scales: {
                x: {
                    type: 'category',
                    title: {
                        display: true,
                        text: 'Meses'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Número de Inscripciones'
                    }
                }
            }
        }
    });
</script>