<?php 

include('vendor/autoload.php');

use Swoole\Coroutine;
use Swoole\Coroutine\Scheduler;
use Swoole\Coroutine\Http\Client;
use Symfony\Component\Dotenv\Dotenv;
use Bot\MessageParser;
use Bot\MessageDispatcher;

$dotenv = new Dotenv();
$dotenv->load(realpath(__DIR__ . '/../../.env'));

echo "[" . date('Y-m-d H:i:s', time()) . "] Starting Twitch Chat... \n";
$run = new Scheduler;
$run->add(function(){

    $twitchIrc = new Client("irc-ws.chat.twitch.tv", 443, true);
    $ret = $twitchIrc->upgrade("/");
    if (!$ret) {
        throw new Exception("Twitch Websocket Upgrade Failed", $twitchIrc->errCode);
    }
    $twitchIrc->push("PASS {$_ENV['TWITCH_OAUTH_PASS']}");
    $twitchIrc->push("NICK {$_ENV['TWITCH_NICK']}");
    $twitchIrc->push("CAP REQ :twitch.tv/commands");
    $twitchIrc->push("CAP REQ :twitch.tv/membership");
    $twitchIrc->push("CAP REQ :twitch.tv/tags");
    $twitchIrc->push("JOIN {$_ENV['TWITCH_ROOM']}");

    $ioTower = new Client('iotower',6889);
    $ret = $ioTower->upgrade("/");
    if (!$ret) {
        throw new Exception("ioTower Websocket Upgrade Failed", $ioTower->errCode);
    }
    $ioTower->push('{"event":"subscribe","payload":["speak"]}');


    Coroutine::create(function()use($twitchIrc, $ioTower){
        $dispatcher = new MessageDispatcher($twitchIrc, $ioTower);
        while (true) {
            if ($data = $twitchIrc->recv()) {
                // Twitch parse Message
                foreach (explode("\n", trim($data->data)) as $line) {
                    $parser = new MessageParser();
                    $dispatcher->dispatch($parser->parse($line));
                }
            }
            Coroutine::sleep(0.5);
        }    
    });
    Coroutine::create(function()use($twitchIrc, $ioTower){
        while (true) {
            if ($data = $ioTower->recv()) {
                // ioTower message handle
                $message = json_decode($data->data, true);
                if (is_array($message) && array_key_exists('event', $message) && $message['event'] === 'speak') {
                    $twitchIrc->push("PRIVMSG {$_ENV['TWITCH_ROOM']} " . $message['payload']);
                }
            }
            Coroutine::sleep(0.5);
        }            
    });

});

$run->start();
