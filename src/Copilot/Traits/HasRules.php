<?php

namespace Elsayed85\CopilotQuery\Copilot\Traits;

trait HasRules
{
    use HasMessage;

    public function addPrompt($prompt): self
    {
        $this->addMessage($prompt, 'system');

        return $this;
    }

    public function addPrompts(array $rules): self
    {
        $rulesAsString = collect($rules)->map(function ($rule) {
            return $rule;
        })->implode("\n");

        if (! empty($rulesAsString)) {
            $this->addMessage($rulesAsString, 'system');
        }

        return $this;
    }
}
