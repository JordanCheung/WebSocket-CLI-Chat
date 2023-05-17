<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatService implements MessageComponentInterface
{
    private $users = [];

    public function onOpen(ConnectionInterface $conn)
    {
        echo 'New user has joined the chat! ' . $conn->resourceId . PHP_EOL;
        $this->users[$conn->resourceId] = $conn;
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo 'User has left the chat! ' . $conn->resourceId . PHP_EOL;
        $users[$conn->resourceId] = null;
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $dotenv = Dotenv\Dotenv::createImmutable('../../');
        $dotenv->load();

        echo $msg . PHP_EOL;

        $jsonDecoded = json_decode($msg);
        $token = $jsonDecoded->token;
        $base64Decoded = base64_decode($token);
        $identity = json_decode($base64Decoded);
        $id = $identity->id;
        $name = $identity->name;
        $sig = $identity->sig;
        $signature = hash_hmac('sha256', $id.$name, $_ENV['HASH_SECRET_KEY']);

        foreach($this->users as $user)
        {
            if (env('USE_WEB_CLIENT') == 'true')
            {
                if ($sig === $signature)
                {
                    $user->send($msg);
                }
            }
            else {
                if ($sig === $signature && $from != $user)
                {
                    $user->send($msg);
                }
            }

        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        error_log($e->getMessage());
        $conn->close();
    }
}