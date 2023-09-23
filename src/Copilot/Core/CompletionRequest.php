<?php

namespace Elsayed85\CopilotQuery\Copilot\Core;

class CompletionRequest
{
    private bool $stream;

    public bool $intent;

    public string $model;

    public float $temperature;

    public int $top_p;

    private int $n;

    public function __construct(public array $messages)
    {
        $this->intent = config('copilot-ask-database.copilot.intent');
        $this->model = config('copilot-ask-database.copilot.model');
        $this->top_p = config('copilot-ask-database.copilot.top_p');
        $this->n = config('copilot-ask-database.copilot.n');
        $this->stream = false;
    }

    public function toArray(): array
    {
        return [
            'stream' => $this->stream,
            'intent' => $this->intent,
            'model' => $this->model,
            'temperature' => $this->temperature,
            'top_p' => $this->top_p,
            'n' => $this->n,
            'messages' => $this->messages,
        ];
    }

    public function isStream(): bool
    {
        return $this->stream;
    }

    public function setStream(bool $stream): static
    {
        $this->stream = $stream;

        return $this;
    }

    public function setTemperature(?float $temperature): static
    {
        if (! $temperature) {
            $temperature = 0.0;
        }

        $this->temperature = $temperature;

        return $this;
    }
}
