<?php
require_once "config.php";

// Variables para almacenar los errores
$username_err = $password_err = "";

// Procesamiento del formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar el nombre de usuario
    if (empty(trim($_POST["username"]))) {
        $username_err = "Por favor, ingresa un nombre de usuario.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validar la contraseña
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor, ingresa una contraseña.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Verificar si no hay errores de validación antes de verificar la autenticidad del usuario
    if (empty($username_err) && empty($password_err)) {
        // Preparar una consulta SELECT para verificar la autenticidad del usuario
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_username);

            // Establecer los parámetros
            $param_username = $username;

            // Intentar ejecutar la consulta
            if ($stmt->execute()) {
                // Almacenar el resultado
                $stmt->store_result();

                // Verificar si el nombre de usuario existe y si la contraseña es correcta
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $username, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // La contraseña es correcta, se inicia la sesión del usuario
                            session_start();
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            header("location: chat.php");
                        } else {
                            // La contraseña es incorrecta
                            $password_err = "La contraseña ingresada es incorrecta.";
                        }
                    }
                } else {
                    // El nombre de usuario no existe
                    $username_err = "El nombre de usuario ingresado no existe.";
                }
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
    <title>Iniciar sesión</title>
    <!-- Agrega los enlaces a los estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="login-container">
            <h2>Iniciar sesión</h2>
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
                <div class="form-group submit-button">
                    <input type="submit" value="Iniciar sesión" class="btn btn-primary">
                </div>
                <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>.</p>
            </form>
        </div>
    </div>
</body>
</html>