<?php

namespace Elsayed85\CopilotQuery\Copilot\Traits;

use Elsayed85\CopilotQuery\Copilot\Core\Message;

trait HasMessage
{
    private array $messages = [];

    public function addMessage($message, $role = 'user'): static
    {
        $this->messages[] = new Message($message, $role);

        return $this;
    }

    public function setMessages(array $messages): static
    {
        foreach ($messages as $message) {
            $this->addMessage($message['content'], $message['role']);
        }

        return $this;
    }
}
