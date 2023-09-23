<?php

declare(strict_types=1);

namespace Elsayed85\CopilotQuery\Copilot\Core\Response;

class CreateResponseChoice
{
    private function __construct(
        public readonly string $content,
        public readonly int $index,
        public readonly ?string $finishReason,
    ) {
    }

    /**
     * @param  array{text: string, index: int, logprobs: array{tokens: array<int, string>, token_logprobs: array<int, float>, top_logprobs: array<int, string>|null, text_offset: array<int, int>}|null, finish_reason: string|null}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['delta']['content'] ?? '',
            $attributes['index'],
            $attributes['finish_reason'],
        );
    }

    /**
     * @return array{text: string, index: int, logprobs: array{tokens: array<int, string>, token_logprobs: array<int, float>, top_logprobs: array<int, string>|null, text_offset: array<int, int>}|null, finish_reason: string|null}
     */
    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'index' => $this->index,
            'finish_reason' => $this->finishReason,
        ];
    }
}
