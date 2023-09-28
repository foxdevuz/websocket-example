const WebSocket = require('ws');

// Create a WebSocket server
const wss = new WebSocket.Server({ port: 8080 });

// Store connected clients
const clients = new Set();

wss.on('connection', (ws) => {
    // Add the new connection to the set of clients
    clients.add(ws);

    console.log(`New connection!`);

    ws.on('message', (message) => {
        console.log(`Received message: ${message}`);
    });

    ws.on('close', () => {
        // Remove the connection when a client disconnects
        clients.delete(ws);
        console.log(`A client has disconnected`);
    });

    ws.on('error', (error) => {
        console.error(`An error occurred: ${error.message}`);
    });
});

console.log('WebSocket server started on port 8080...');