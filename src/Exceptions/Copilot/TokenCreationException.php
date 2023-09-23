<?php

namespace Elsayed85\CopilotQuery\Exceptions\Copilot;

class TokenCreationException extends \Exception
{
    public array $details;

    public function __construct($message, $code, $details = [])
    {
        parent::__construct($message, $code);
        $this->details = $details;
    }
}
