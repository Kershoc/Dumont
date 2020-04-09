<?php
declare(strict_types=1);

namespace Bot\Handlers;

use Bot\MessageObject;
use Swoole\Coroutine\http\Client;

class Privmsg implements MessageHandler
{
    private $ioTower;
    private $twitchIrc;

    public function __construct(Client $twitchIrc, Client $ioTower)
    {
        $this->ioTower = $ioTower;
        $this->twitchIrc = $twitchIrc;
    }

    public function handle(MessageObject $message_object): void
    {
        // In a PRIVMSG $message_object->options are our chat message
        if ($message_object->options[0] === "!") {
                // Bot Command, broadcast to the mcp
                $command = [
                    'event' => 'bang-command',
                    'payload' => $message_object
                ];
                $this->ioTower->push(json_encode($command));
            }

        // Chat Message; Send to tower
        $chat = [
            'event' => 'chat',
            'payload' => $message_object
        ];
        $this->ioTower->push(json_encode($chat));
    }

}
