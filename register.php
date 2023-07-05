<?php
require_once "config.php";

// Variables para almacenar los errores
$username_err = $password_err = $confirm_password_err = "";

// Procesamiento del formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar el nombre de usuario
    if (empty(trim($_POST["username"]))) {
        $username_err = "Por favor, ingresa un nombre de usuario.";
    } else {
        // Preparar una consulta SELECT para verificar si el nombre de usuario ya existe
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_username);

            // Establecer los parámetros
            $param_username = trim($_POST["username"]);

            // Intentar ejecutar la consulta
            if ($stmt->execute()) {
                // Almacenar el resultado
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $username_err = "Este nombre de usuario ya está en uso.";
                }
            } else {
                echo "Oops! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
            }

            // Cerrar la declaración
            $stmt->close();
        }
    }

    // Validar la contraseña
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor, ingresa una contraseña.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "La contraseña debe tener al menos 6 caracteres.";
    }

    // Validar la confirmación de la contraseña
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Por favor, confirma la contraseña.";
    } else {
        $password = trim($_POST["password"]);
        $confirm_password = trim($_POST["confirm_password"]);

        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Las contraseñas no coinciden.";
        }
    }

    // Verificar si no hay errores de validación antes de insertar los datos en la base de datos
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        // Preparar una consulta INSERT para insertar los datos del nuevo usuario en la base de datos
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $param_username, $param_password);

            // Establecer los parámetros
            $param_username = trim($_POST["username"]);
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Encriptar la contraseña

            // Intentar ejecutar la consulta
            if ($stmt->execute()) {
                // Redirigir al usuario a la página de inicio de sesión después de un registro exitoso
                header("location: login.php");
            } else {
                echo "Oops! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
            }

            // Cerrar la declaración
            $stmt->close();
        }
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registro</title>
    <!-- Agrega los enlaces a los estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="registration-container">
            <h2>Registro</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Nombre de usuario:</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Contraseña:</label>
                    <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Confirmar contraseña:</label>
                    <input type="password" name="confirm_password" class="form-control"
                        value="<?php echo $confirm_password; ?>">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group submit-button">
                    <input type="submit" value="Registrarse" class="btn btn-primary">
                </div>
                <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
            </form>
        </div>
    </div>
</body>

</html>

