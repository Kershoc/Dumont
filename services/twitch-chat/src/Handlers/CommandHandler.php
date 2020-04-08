<?php

namespace Bot\Handlers;

use Bot\MessageObject;

interface CommandHandler
{
    public function handle(MessageObject $messageObject): void;
}
