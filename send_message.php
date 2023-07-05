<?php
require_once "config.php";

if (isset($_GET["message"]) && !empty($_GET["message"])) {
    // Obtener el mensaje, remitente y receptor
    $message = $_GET["message"];
    $sender = "Usuario1"; // Cambia "Usuario1" por el remitente deseado
    $receiver = "Usuario2"; // Cambia "Usuario2" por el receptor deseado

    if (isset($_GET["sender"]) && !empty($_GET["sender"])) {
        $sender = $_GET["sender"];
    }

    if (isset($_GET["receiver"]) && !empty($_GET["receiver"])) {
        $receiver = $_GET["receiver"];
    }
    // Insertar el mensaje en la base de datos
    $sql = "INSERT INTO messages (sender, receiver, message) VALUES ('$sender', '$receiver', '$message')";
    if ($conn->query($sql) === TRUE) {
        echo "Mensaje enviado correctamente";
    } else {
        echo "Error al enviar el mensaje: " . $conn->error;
    }
} else {
    echo "Mensaje vacío";
}

$conn->close();
?>
