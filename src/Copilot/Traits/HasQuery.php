<?php

namespace Elsayed85\CopilotQuery\Copilot\Traits;

use Elsayed85\CopilotQuery\Copilot\Core\CompletionRequest;
use Elsayed85\CopilotQuery\Exceptions\Copilot\CopilotQueryException;
use Elsayed85\CopilotQuery\Exceptions\Copilot\NoMessagesToSendException;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\ResponseInterface;

trait HasQuery
{
    use HasToken;

    /**
     * @throws NoMessagesToSendException
     * @throws CopilotQueryException
     */
    private function query(CompletionRequest $completionRequest): ResponseInterface|array
    {
        if (empty($this->messages)) {
            throw new NoMessagesToSendException('Please add messages to send');
        }

        $response = Http::withHeaders(['User-Agent' => config('copilot-ask-database.copilot.user_agent')])
            ->asJson()
            ->withToken($this->getToken())
            ->post('https://copilot-proxy.githubusercontent.com/v1/chat/completions', $completionRequest->toArray());

        if ($response->status() != 200) {
            throw new CopilotQueryException("Could not query copilot: {$response->body()}");
        }

        if ($completionRequest->isStream()) {
            return $response->toPsrResponse();
        }

        return $response->json();
    }
}
