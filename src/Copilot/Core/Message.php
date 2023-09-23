<?php

namespace Elsayed85\CopilotQuery\Copilot\Core;

class Message
{
    public function __construct(public string $content, public string $role)
    {
        //
    }
}
