<?php

namespace Bot;

use Swoole\WebSocket\Server;

class Handlers
{
    const HELO = "Yes, I'm old. Old enough to remember the MCP when he was just a chess program. He started small, and he'll end small.";

    private $IOTower;

    public function __construct(IOTower $IOTower)
    {
        $this->IOTower = $IOTower;
    }

    public function onMessage(Server $svr, $frame)
    {
        $data = json_decode($frame->data, true);
        if (is_array($data) && array_key_exists('event', $data)) {
            // Subscribe
            if ($data['event'] === 'subscribe') {
                foreach ($data['payload'] as $roomName) {
                    $this->IOTower->getRoom($roomName)->join($frame->fd);
                    $svr->push($frame->fd, '{"msg":"Subscribed to '.$roomName.'"}');
                }
                return;
            }
            // Unsubscribe
            if ($data['event'] === 'unsubscribe') {
                foreach ($data['payload'] as $roomName) {
                    $this->IOTower->getRoom($roomName)->leave($frame->fd);
                    $svr->push($frame->fd, '{"msg":"Unsubscribed to '.$roomName.'"}');
                }
                return;
            }
            // Broadcast
            if ($data['event'] === 'broadcast') {
                foreach ($svr->getClientList(0) as $fd) {
                    $svr->push($fd, $frame->data);
                }
            }

            // Broadcast event to its room
            if ($this->IOTower->hasRoom($data['event'])) {
                foreach ($this->IOTower->getRoom($data['event'])->getConnections() as $fd) {
                    $svr->push($fd, $frame->data);
                }
            }
        }
    }

    public function onOpen(Server $svr, $request)
    {
        $svr->push($request->fd, '{"msg": "'.self::HELO.'"}');
    }

    public function onClose(Server $svr, int $fd)
    {
        $this->IOTower->evict($fd);
    }
}
