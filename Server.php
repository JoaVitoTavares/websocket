<?php

namespace WebsocketTest;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require __DIR__ . '/vendor/autoload.php';


$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new WebSocket()
        )
    ),
    8080
);

$data_str = var_export("chega até aqui 99", true);

// Write the data into the file
$file_path = __DIR__ . '/dump.txt';
file_put_contents($file_path, $data_str . "\n", FILE_APPEND);

$server->run();


