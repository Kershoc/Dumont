<?php

namespace Bot\Handlers;

use Bot\MessageObject;

interface MessageHandler
{
    public function handle(MessageObject $messageObject): void;
}
