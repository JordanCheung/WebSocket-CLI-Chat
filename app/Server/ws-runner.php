<?php

require '../../vendor/autoload.php';
require 'ChatService.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$dotenv = Dotenv\Dotenv::createImmutable('../../');
$dotenv->load();

$chatService = new ChatService();
$wsServer = new WsServer($chatService);
$httpServer = new HttpServer($wsServer);

IoServer::factory($httpServer, $_ENV['WEB_SOCKET_PORT'])->run();
