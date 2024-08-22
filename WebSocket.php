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
        $data_str = var_export("não foi\n", true);

// Write the data into the file
        $file_path = __DIR__ . '/dump.txt';
        file_put_contents($file_path, $data_str . "\n", FILE_APPEND);
        $this->clients->attach($conn);
        $data_str = var_export("foi\n", true);

// Write the data into the file
        $file_path = __DIR__ . '/dump.txt';
        file_put_contents($file_path, $data_str . "\n", FILE_APPEND);

    }

    function onClose(ConnectionInterface $conn)
    {
        $data_str = var_export("fechando\n", true);
        $file_path = __DIR__ . '/dump.txt';
        file_put_contents($file_path, $data_str . "\n", FILE_APPEND);


        $this->clients->detach($conn);

        $data_str = var_export("fechado\n", true);
        $file_path = __DIR__ . '/dump.txt';
        file_put_contents($file_path, $data_str . "\n", FILE_APPEND);
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
        // Get a string representation of the data
        $data_str = var_export($e, true);

        // Write the data into the file
        $file_path = __DIR__ . '/dump.txt';
        file_put_contents($file_path, $data_str);

        error_log($e->getMessage(). "\n \n", 3, __DIR__ . "/error.txt");

        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->clients as $client) {
            if ($from != $client) {
                $data_str = var_export("vai enviar menssagem\n", true);
                $file_path = __DIR__ . '/dump.txt';
                file_put_contents($file_path, $data_str . "\n", FILE_APPEND);

                $client->send($msg);

                $data_str = var_export("enviou! {$msg} \n", true);
                $file_path = __DIR__ . '/dump.txt';
                file_put_contents($file_path, $data_str . "\n", FILE_APPEND);
            }
        }
    }
}