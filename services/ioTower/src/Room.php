<?php

namespace Bot;

class Room
{
    private $connections = [];
    private $name;

    public function __construct(string $roomName)
    {
        $this->name = $roomName;
    }

    public function join(int $connectionId): void
    {
        if (!in_array($connectionId, $this->connections)) {
            array_push($this->connections, $connectionId);
        }
    }

    public function leave(int $connectionId): void
    {
        $this->connections = array_filter($this->connections, function($v) use($connectionId) {return $v !== $connectionId;});
    }

    public function getConnections(): array
    {
        return $this->connections;
    }

    public function getName(): string
    {
        return $this->name;
    }
}