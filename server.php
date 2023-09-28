<?php
require 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

// Create a class that implements the WebSocket server logic
class MyWebSocket implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection when a client connects
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo $msg;
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Remove the connection when a client disconnects
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // Handle errors here, e.g., log or close the connection
        echo "An error occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Create the WebSocket server using Ratchet
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new MyWebSocket() // Pass an instance of your WebSocket class
        )
    ),
    8080 // You can change the port if needed
);

echo "WebSocket server started on port 8080...\n";

$server->run(); 