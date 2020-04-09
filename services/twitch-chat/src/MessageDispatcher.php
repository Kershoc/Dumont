<?php

namespace Bot;

use Swoole\Coroutine\http\Client;

class MessageDispatcher
{
    private $ioTower;
    private $twitchIrc;

    public function __construct(Client $twitchIrc, Client $ioTower)
    {
        $this->ioTower = $ioTower;
        $this->twitchIrc = $twitchIrc;
    }

    public function dispatch(MessageObject $message_object): void
    {
        $message_class = "Bot\\Handlers\\" . ucwords(strtolower($message_object->command));
        if (class_exists($message_class)) {
            $handler = new $message_class($this->twitchIrc, $this->ioTower);
            $handler->handle($message_object);
        }
    }
}
