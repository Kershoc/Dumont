<?php

namespace Bot;

use Swoole\Coroutine\http\Client;

class CommandDispatcher
{
    private $ioTower;

    public function __construct(Client $ioTower)
    {
        $this->ioTower = $ioTower;
    }

    public function dispatch(MessageObject $message_object): void
    {
            $command = $this->parseCommand($message_object->options);
            if ($this->botCommandExists($command)) {
                $this->runBotCommand($command, $message_object);
            }
    }

    private function parseCommand(string $message): string
    {
        $has_args = strpos($message, " ");
        if ($has_args !== false) {
            $command = substr($message, 1, $has_args - 1);
        } else {
            $command = substr($message, 1);
        }
        return $command;
    }

    private function botCommandClass(string $command): string
    {
        return "Bot\\Commands\\" . ucwords(strtolower($command) . "Command");
    }

    private function botCommandExists($command): bool
    {
        $commandClass = $this->botCommandClass($command);
        if (class_exists($commandClass)) {
            return true;
        }
        return false;
    }

    private function runBotCommand(string $command, MessageObject $messageObject)
    {
        $commandClass = $this->botCommandClass($command);
        $cmd = new $commandClass($this->ioTower);
        $cmd->run($messageObject);
    }

}
