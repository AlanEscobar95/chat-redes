<?php
require_once "config.php";

$sender = "Usuario1"; // Cambia "Usuario1" por el remitente deseado
$receiver = "Usuario2"; // Cambia "Usuario2" por el receptor deseado

$sql = "SELECT * FROM messages WHERE (sender = '$sender' AND receiver = '$receiver') OR (sender = '$receiver' AND receiver = '$sender') ORDER BY timestamp ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $messageSender = $row["sender"];
        $messageContent = $row["message"];
        if ($messageSender == $sender) {
            echo "<p><strong>" . $messageSender . ":</strong> " . $messageContent . "</p>";
        } else {
            echo "<p><strong>" . $messageSender . ":</strong> " . $messageContent . "</p>";
        }
    }
} else {
    echo "<p>No hay mensajes.</p>";
}

$conn->close();
?>
