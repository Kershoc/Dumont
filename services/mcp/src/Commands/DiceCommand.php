<?php

namespace Bot\Commands;

use Bot\MessageObject;
use Swoole\Coroutine\http\Client;

class DiceCommand implements Command
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
            "payload" => "You can roll a !d20 or a !d6"
        ];
        $this->ioTower->push(json_encode($data));
    }
}
