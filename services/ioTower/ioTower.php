<?php

require_once('vendor/autoload.php');

use Bot\Handlers;
use Bot\IOTower;
use Swoole\Websocket\Server;

$server = new Server('iotower', 6889);

$server->set(array(
                       'worker_num' => 1, // The number of worker processes
                       'daemonize' => false, // Whether start as a daemon process
                       'backlog' => 128, // TCP backlog connection number
                   ));
$handlers = new Handlers(new IOTower());

$server->on('message', [$handlers, 'onMessage']);
$server->on('open', [$handlers, 'onOpen']);
$server->on('close', [$handlers, 'onClose']);
echo "[" . date('Y-m-d H:i:s', time()) . "] Starting IoTower... \n";
$server->start();
