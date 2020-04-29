<?php

namespace Bot\Commands;

use Bot\MessageObject;
use Swoole\Coroutine\http\Client;

class SocialCommand implements Command
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
            "payload" => "Twitter: {$_ENV['SOCIAL_URL_TWITTER']}  GitHub: {$_ENV['SOCIAL_URL_GITHUB']}"
        ];
        $this->ioTower->push(json_encode($data));
    }
}
