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