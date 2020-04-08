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
        $command_class = "Bot\\Handlers\\" . ucwords(strtolower($message_object->command));
        if (class_exists($command_class)) {
            $handler = new $command_class($this->twitchIrc, $this->ioTower);
            $handler->handle($message_object);
        }
    }
}
