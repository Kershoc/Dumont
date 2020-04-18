<?php

namespace Bot\Commands;

use Bot\MessageObject;
use Swoole\Coroutine\http\Client;

class SpeakCommand implements Command
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
            "payload" => "Hello and welcome.  End Of Line."
        ];
        $this->ioTower->push(json_encode($data));
    }
}
