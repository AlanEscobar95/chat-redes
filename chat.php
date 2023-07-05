<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario no ha iniciado sesión, redirigir al formulario de inicio de sesión
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Yavi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.2/socket.io.min.js"></script>
    <script src="funciones.js"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h2>YaviChat</h2>
                    </div>
                    <div class="card-body chat-box" id="chat-box"></div>
                    <div class="card-footer input-group">
                        <input type="text" id="message" class="form-control" placeholder="Escribe un mensaje">
                        <div class="button-container">
                        <button onclick="sendMessage()" class="btn btn-primary">Enviar</button>
                        <button onclick="switchToUser2()" class="btn btn-secondary">Cambiar a Usuario 2</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
