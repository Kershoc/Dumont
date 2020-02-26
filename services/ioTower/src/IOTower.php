<?php

namespace Bot;

class IOTower
{
    private $rooms = [];

    public function __construct()
    {
    }

    public function add(Room $room): void
    {
        if (!$this->hasRoom($room->getName())) {
            $this->rooms[$room->getName()] = $room;
        }
    }

    public function remove(Room $room): void
    {
        if ($this->hasRoom($room)) {
            unset($this->rooms[$room->getname()]);
        }
    }

    public function hasRoom(string $roomName): bool
    {
        return array_key_exists($roomName, $this->rooms);
    }

    public function getRoom(string $roomName): Room
    {
        if (!$this->hasRoom($roomName)) {
            $this->buildRoom($roomName);
        }
        return $this->rooms[$roomName];
    }

    public function evict(int $connectionId): void
    {
        foreach ($this->rooms as $room) {
            $room->leave($connectionId);
        }
    }

    private function buildRoom($roomName)
    {
        $room = new Room($roomName);
        $this->add($room);
    }
}
