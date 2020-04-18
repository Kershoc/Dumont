<?php

namespace Bot\Commands;

use Bot\MessageObject;
use Swoole\Coroutine\http\Client;

class D20Command implements Command
{
    private $ioTower;

    public function __construct(Client $ioTower)
    {
        $this->ioTower = $ioTower;
    }

    public function run(MessageObject $message_object): void
    {
        $num = $this->rollD20();
        if ($num === 20) {
            $response = $message_object->tags['display-name'] . ' CRITS! ' . $num;
        } elseif ($num === 1) {
            $response = $message_object->tags['display-name'] . ' rolls ' . $num . ' Critical Fail!';
        } else {
            $response = $message_object->tags['display-name'] . ' has rolled a ' . $num;
        }

        $data = [
            "event" => "speak",
            "payload" => $response
        ];
        $this->ioTower->push(json_encode($data));
    }

    public function rollD20()
    {
        return rand(1, 20);
    }

}
