<?php

namespace Bot\Handlers;

use Bot\MessageObject;
use Swoole\Coroutine\http\Client;

class Ping implements CommandHandler
{
    private $ioTower;
    private $twitchIrc;

    public function __construct(Client $twitchIrc, Client $ioTower)
    {
        $this->ioTower = $ioTower;
        $this->twitchIrc = $twitchIrc;
    }

    public function handle(MessageObject $messageObject): void
    {
        echo "> PONG :tmi.twitch.tv \n";
        $this->twitchIrc->push('PONG :tmi.twitch.tv');
    }
}
