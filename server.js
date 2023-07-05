const http = require('http');
const server = http.createServer();
const io = require('socket.io')(server);

io.on('connection', (socket) => {
    console.log('Usuario conectado');

    socket.on('chatMessage', (message) => {
        // Emitir el evento a todos los clientes conectados para recibir el mensaje
        io.emit('messageReceived', message);
    });

    socket.on('disconnect', () => {
        console.log('Usuario desconectado');
    });
});

server.listen(3000, () => {
    console.log('Servidor de Socket.IO escuchando en el puerto 3000');
});
