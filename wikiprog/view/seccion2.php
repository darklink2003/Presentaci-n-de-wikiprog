<!--
/**
 * seccion2.php
 * Formulario de inicio de sesión con código de verificación.
 *
 * Este formulario permite a los usuarios ingresar sus credenciales
 * para iniciar sesión en el sistema. Incluye un código de verificación
 * para aumentar la seguridad del proceso.
 *
 * @version 1.0
 * @author Pablo Alexander Mondragon Acevedo
 */
-->


<!-- Estructura principal del formulario -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-body">
                    <!-- Título del formulario -->
                    <h3 class="card-title text-center">Inicio de Sesión</h3>

                    <!-- Formulario de inicio de sesión -->
                    <form action="../controller/iniciar.php" method="post" id="formulario-login">

                        <!-- Campo para ingresar el nombre de usuario -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="Ingresa tu nombre de usuario" required aria-label="Nombre de Usuario">
                        </div>

                        <!-- Campo para ingresar la contraseña -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Ingresa tu contraseña" required aria-label="Contraseña">
                                <button class="btn btn-outline-secondary" type="button"
                                    id="toggle-password">Mostrar</button>
                            </div>
                        </div>

                        <!-- Campo para ingresar el código de verificación -->
                        <div class="mb-3">
                            <label for="verification-code" class="form-label">Código de Verificación</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="verification-code-input"
                                    name="verification_code" placeholder="Ingresa el código de verificación" required
                                    aria-label="Código de Verificación">
                                <span class="input-group-text" id="generated-code">Xa43-24</span>
                                <button class="btn btn-outline-secondary" type="button" id="refresh-code">↻</button>
                            </div>
                        </div>

                        <!-- Botón para enviar el formulario -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                        </div>

                        <!-- Enlace para redirigir al usuario a la página de registro -->
                        <div class="mt-3 text-center">
                            <a href="controlador.php?seccion=seccion5" class="btn btn-link">Registrarse</a>
                        </div>
                        <!-- Enlace para redirigir al usuario a la página de recuperacion de contraseña -->
                        <div class="mt-3 text-center">
                            <a href="controlador.php?seccion=seccion20" class="btn btn-link">recuperar contraseña</a>
                        </div>
                    </form>

                    <!-- Mensaje de error en caso de que exista algún error -->
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger mt-3" role="alert">
                            <?php echo htmlspecialchars($_GET['error']); ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script JavaScript para funcionalidades adicionales -->
<script>
    // Función para mostrar u ocultar la contraseña
    document.getElementById('toggle-password').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const passwordFieldType = passwordField.getAttribute('type');
        if (passwordFieldType === 'password') {
            passwordField.setAttribute('type', 'text');
            this.textContent = 'Ocultar';
        } else {
            passwordField.setAttribute('type', 'password');
            this.textContent = 'Mostrar';
        }
    });

    // Función para generar el código de verificación
    function generateVerificationCode() {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let code = '';

        // Generar 4 caracteres alfanuméricos aleatorios y un guion
        for (let i = 0; i < 4; i++) {
            code += characters.charAt(Math.floor(Math.random() * characters.length));
        }

        code += '-';

        // Generar 2 números aleatorios
        for (let i = 0; i < 2; i++) {
            code += Math.floor(Math.random() * 10);
        }

        return code;
    }

    // Función para validar el código de verificación
    function validateVerificationCode(inputCode, generatedCode) {
        return inputCode === generatedCode;
    }

    // Cuando el documento HTML está completamente cargado
    document.addEventListener('DOMContentLoaded', function () {
        // Generar un código de verificación inicial y mostrarlo en el span correspondiente
        const generatedCode = generateVerificationCode();
        document.getElementById('generated-code').textContent = generatedCode;

        // Evento para refrescar el código de verificación cuando se hace clic en el botón correspondiente
        document.getElementById('refresh-code').addEventListener('click', function () {
            const newCode = generateVerificationCode();
            document.getElementById('generated-code').textContent = newCode;
        });

        // Manejar el envío del formulario
        document.getElementById('formulario-login').addEventListener('submit', function (event) {
            // Obtener el código ingresado por el usuario y el código generado actualmente
            const inputCode = document.getElementById('verification-code-input').value;
            const generatedCode = document.getElementById('generated-code').textContent;

            // Validar el código de verificación
            if (!validateVerificationCode(inputCode, generatedCode)) {
                event.preventDefault(); // Evitar que se envíe el formulario si el código es incorrecto
                const mensajeContainer = document.getElementById('mensaje-container');
                mostrarMensajeEnContenedor(mensajeContainer, 'El código de verificación es incorrecto. Por favor, intenta de nuevo.', 'danger');
            }
        });
    });
</script>