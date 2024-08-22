<?php

namespace WebsocketTest;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class WebSocket implements MessageComponentInterface
{

    public function  __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
        // Get a string representation of the data
        $data_str = var_export($e, true);

        // Write the data into the file
        $file_path = __DIR__ . '/dump.txt';
        file_put_contents($file_path, $data_str);

        error_log($e->getMessage(), 3, __DIR__ . "/error.txt");

        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->clients as $client) {
            if ($from != $client) {
                $client->send($msg);
            }
        }
    }
}