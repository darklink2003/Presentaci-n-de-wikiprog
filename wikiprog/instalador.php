<!-- instalador.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Instalador de Base de Datos</title>
    <!-- Incluyendo Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #343a40;
            color: white;
        }
        .form-container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background-color: #212529;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        }
        .form-container label {
            color: #ced4da;
        }
        .form-container .btn-primary {
            background-color: #007bff;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mt-5">Configuración de la Base de Datos</h1>
        <div class="form-container mt-4">
            <form action="instalando.php" method="post">
                <div class="form-group">
                    <label for="host">Host:</label>
                    <input type="text" id="host" name="host" class="form-control" value="127.0.0.1" required>
                </div>
                <div class="form-group">
                    <label for="db_name">Nombre de la Base de Datos:</label>
                    <input type="text" id="db_name" name="db_name" class="form-control" value="wikiprog" required>
                </div>
                <div class="form-group">
                    <label for="username">Usuario:</label>
                    <input type="text" id="username" name="username" class="form-control" value="root" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Instalar</button>
            </form>
        </div>
    </div>
    <!-- Incluyendo Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
