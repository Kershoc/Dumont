<?php

// Command Processor to process !commands 
// Master Command Processor

use Swoole\Coroutine;
use Swoole\Coroutine\Scheduler;
use Swoole\Coroutine\Http\Client;
use Symfony\Component\Dotenv\Dotenv;

echo "[" . date('Y-m-d H:i:s', time()) . "] Starting MCP... End Of Line\n";
$run = new Scheduler();
$run->add(function(){
    $ioTower = new Client('iotower',6889);
    $ret = $ioTower->upgrade("/");
    if (!$ret) {
        throw new Exception("ioTower Websocket Upgrade Failed", $ioTower->errCode);
    }
    $ioTower->push('{"event":"subscribe","payload":["bang-command"]}');

    Coroutine::create(function()use($twitchIrc, $ioTower){
        while (true) {
            if ($data = $ioTower->recv()) {
                // ioTower message handle
                $message = json_decode($data->data, true);
                if (is_array($message) && array_key_exists('event', $message) && $message['event'] === 'bang-command') {
                    // Parse Bot Command
                    
                }
            }
            Coroutine::sleep(0.5);
        }            
    });

});

$run->start();
