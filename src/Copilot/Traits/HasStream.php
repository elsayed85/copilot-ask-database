<?php

namespace Elsayed85\CopilotQuery\Copilot\Traits;

use Elsayed85\CopilotQuery\Copilot\Core\Response\CreateStreamedResponse;
use Elsayed85\CopilotQuery\Copilot\Core\Response\StreamResponse;
use Psr\Http\Message\ResponseInterface;

trait HasStream
{
    private string $responseClass = CreateStreamedResponse::class;

    /**
     * @throws \JsonException
     * @throws \ErrorException
     */
    private function stream(ResponseInterface $response, $stop = "\n"): \Generator
    {
        return (new StreamResponse($this->responseClass, $response, $stop))->getIterator();
    }
}
