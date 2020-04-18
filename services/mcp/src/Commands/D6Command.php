<?php

namespace Bot\Commands;

use Bot\MessageObject;
use Swoole\Coroutine\http\Client;

class D6Command implements Command
{
    private $ioTower;

    public function __construct(Client $ioTower)
    {
        $this->ioTower = $ioTower;
    }

    public function run(MessageObject $message_object): void
    {
        $data = [
            "event" => "speak",
            "payload" => $message_object->tags['display-name'] . ' has rolled a ' . rand(1, 6)
        ];
        $this->ioTower->push(json_encode($data));
    }
}
