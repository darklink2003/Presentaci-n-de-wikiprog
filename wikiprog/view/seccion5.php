<!--
/**
 * seccion5.php
 * Formulario para el registro de nuevos usuarios con código de verificación y validación de contraseña.
 *
 * Este formulario permite a los usuarios ingresar información para registrarse
 * en el sistema. Se solicita el nombre de usuario, correo electrónico, biografía
 * y contraseña. Al enviar el formulario, los datos se envían al archivo "registro.php"
 * mediante el método POST para su procesamiento.
 * 
 * @version 1.0
 * @author Pablo Alexander Mondragon Acevedo
 */
-->

<!-- Estructura principal del formulario de registro -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-body">
                    <!-- Título del formulario -->
                    <h3 class="card-title text-center">Registro de Usuario</h3>
                    
                    <!-- Formulario de registro -->
                    <form action="../controller/registro.php" method="post" id="formulario-registro">
                        
                        <!-- Campo para ingresar el nombre de usuario -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Ingresa tu nombre de usuario" required>
                        </div>
                        
                        <!-- Campo para ingresar el correo electrónico -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Ingresa tu correo electrónico" required>
                        </div>
                        
                        <!-- Campo para ingresar la biografía -->
                        <div class="mb-3">
                            <label for="biografia" class="form-label">Biografía</label>
                            <input type="text" class="form-control" id="biografia" name="biografia" placeholder="Ingresa tu biografía" required>
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
                                    name="verification_code" placeholder="Ingresa el código de verificación"
                                    required aria-label="Código de Verificación">
                                <span class="input-group-text" id="generated-code">Xa43-24</span>
                                <button class="btn btn-outline-secondary" type="button" id="refresh-code">↻</button>
                            </div>
                        </div>
                        
                        <!-- Contenedor para mensajes -->
                        <div id="mensaje-container"></div>
                        
                        <!-- Checkbox para aceptar términos y condiciones -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terminosCheckbox" name="terminos" required>
                            <label class="form-check-label" for="terminosCheckbox">Acepto los términos y condiciones</label>
                        </div>
                        
                        <!-- Contenedor para mostrar los términos y condiciones -->
                        <div class="mb-3" id="terminosIframeContainer" style="display: none;">
                            <iframe src="../view/terminos_y_condiciones.php" width="100%" height="200" frameborder="0"></iframe>
                        </div>
                        
                        <!-- Botón para enviar el formulario -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" id="submitBtn">Registrarse</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script JavaScript para mostrar u ocultar los términos y condiciones, manejar el código de verificación y validar la contraseña -->
<script>
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

    // Función para validar la contraseña
    function validatePassword(password) {
        const regex = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/;
        return regex.test(password);
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

        // Evento para mostrar u ocultar la contraseña cuando se hace clic en el botón correspondiente
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

        // Manejar el envío del formulario
        document.getElementById('formulario-registro').addEventListener('submit', function (event) {
            // Obtener la contraseña ingresada por el usuario
            const password = document.getElementById('password').value;

            // Validar la contraseña
            if (!validatePassword(password)) {
                event.preventDefault(); // Evitar que se envíe el formulario si la contraseña no cumple con los requisitos
                const mensajeContainer = document.getElementById('mensaje-container');
                mostrarMensajeEnContenedor(mensajeContainer, 'La contraseña debe contener al menos una letra mayúscula, un número, un caracter especial y tener una longitud mínima de 8 caracteres.', 'danger');
            }

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

        // JavaScript para mostrar u ocultar los términos y condiciones al hacer clic en el checkbox
        document.getElementById('terminosCheckbox').addEventListener('change', function () {
            var iframeContainer = document.getElementById('terminosIframeContainer');
            if (this.checked) {
                iframeContainer.style.display = 'block'; // Mostrar iframe si se aceptan los términos
            } else {
                iframeContainer.style.display = 'none'; // Ocultar iframe si no se aceptan los términos
            }
        });
    });
</script>
