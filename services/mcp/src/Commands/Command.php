<?php

namespace Bot\Commands;

use Bot\MessageObject;

interface Command
{
    public function run(MessageObject $message_object): void;
}
