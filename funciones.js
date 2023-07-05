var sender = "Usuario1"; // Remitente inicial
        var receiver = "Usuario2"; // Receptor inicial

        function getMessages() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("chat-box").innerHTML = this.responseText;
                    scrollToBottom();
                }
            };
            xhttp.open("GET", "get_messages.php?sender=" + encodeURIComponent(sender) + "&receiver=" + encodeURIComponent(receiver), true);
            xhttp.send();
        }

        function sendMessage() {
            var message = document.getElementById("message").value;
            if (message.trim() === "") {
                return; // No enviar mensajes vacíos
            }
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("message").value = "";
                    scrollToBottom();
                }
            };
            xhttp.open("GET", "send_message.php?message=" + encodeURIComponent(message) + "&sender=" + encodeURIComponent(sender) + "&receiver=" + encodeURIComponent(receiver), true);
            xhttp.send();

            // Emitir el evento de Socket.IO para enviar el mensaje en tiempo real
            socket.emit('chatMessage', message);
        }

        function scrollToBottom() {
            var chatBox = document.getElementById("chat-box");
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        var socket = io();

        socket.on('connect', function () {
            console.log('Conectado al servidor de Socket.IO');
        });

        socket.on('disconnect', function () {
            console.log('Desconectado del servidor de Socket.IO');
        });

        socket.on('messageReceived', function (message) {
            var chatBox = document.getElementById("chat-box");
            var newMessage = document.createElement("p");
            newMessage.innerHTML = "<strong>Otro usuario:</strong> " + message;
            chatBox.appendChild(newMessage);
            scrollToBottom();
        });

        // Actualizar los mensajes cada 2 segundos
        setInterval(getMessages, 2000);

        function switchToUser2() {
            sender = "Usuario2";
            receiver = "Usuario1";
            console.log("Cambiado a Usuario 2");
            alert("Te has cambiado a Usuario 2");
        }
