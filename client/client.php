<?php
require __DIR__ . '/vendor/autoload.php';

use React\EventLoop\Factory;
use React\Stream\ReadableResourceStream;
use React\Stream\WritableResourceStream;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$loop    = Factory::create();
$stdout  = new WritableResourceStream(STDOUT, $loop);
$stdin   = new ReadableResourceStream(STDIN, $loop);

$jsonUser = base64_decode($_ENV['CLIENT_SECRET_KEY']);
$userInfo = json_decode($jsonUser);
$whoAmI = $userInfo->name;

\Ratchet\Client\connect($_ENV['WEB_SOCKET_SERVER'], [], [], $loop)->then(function($conn) use($stdin, $whoAmI) {

    echo "{$whoAmI}> ";

    $conn->on('message', function($msg) use ($conn, $whoAmI) {
        $object = json_decode($msg);
        $signature = $object->token;
        $decoded = base64_decode($signature);
        $userInfo = json_decode($decoded);
        $name = $userInfo->name;

        $message = $object->message;

        echo "\n{$name}> {$message}\n";
        echo "{$whoAmI}> ";

    });

    $stdin->on('data', function($e) use($conn, $whoAmI) {

        $input = trim($e);

        $message['message'] = $input;
        $message['token'] = $_ENV['CLIENT_SECRET_KEY'];

        $json = json_encode($message);

        if ($input === "!exit")
        {
            echo "!Leaving Chat\n";
            exit(1);
        }
        elseif (trim($input) != "")
        {
            $conn->send($json);
        }
        echo "{$whoAmI}> ";
    });

}, function ($e) {
    echo "Could not connect: {$e->getMessage()}\n";
});

$loop->run();


