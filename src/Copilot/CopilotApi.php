<?php

namespace Elsayed85\CopilotQuery\Copilot;

use Elsayed85\CopilotQuery\Copilot\Core\CompletionRequest;
use Elsayed85\CopilotQuery\Copilot\Traits\HasQuery;
use Elsayed85\CopilotQuery\Copilot\Traits\HasRules;
use Elsayed85\CopilotQuery\Copilot\Traits\HasStream;
use Elsayed85\CopilotQuery\Exceptions\Copilot\CopilotQueryException;
use Elsayed85\CopilotQuery\Exceptions\Copilot\NoMessagesToSendException;
use Elsayed85\CopilotQuery\Exceptions\Copilot\TokenCreationException;

class CopilotApi
{
    use HasQuery , HasRules , HasStream;

    private int $delay = 10000;

    /**
     * @throws TokenCreationException
     */
    public function __construct()
    {
        $this->setGithubToken(config('copilot-ask-database.github_token'));
        $this->getOrRefreshToken();
    }

    /**
     * @throws NoMessagesToSendException
     * @throws CopilotQueryException
     */
    public function send(?float $temperature, ?string $stop): string
    {
        $results = $this->query(
            completionRequest : (new CompletionRequest($this->messages))->setTemperature($temperature)
        );

        $content = $results['choices'][0]['message']['content'];

        if ($stop) {
            $content = explode($stop, $content)[0];
        }

        return $content;
    }

    /**
     * @throws CopilotQueryException
     * @throws NoMessagesToSendException
     * @throws \JsonException
     * @throws \ErrorException
     */
    public function streamResponse(float $temperature = 0.1, string $stop = "\n"): \Generator
    {
        return $this->stream(
            response : $this->query(
                completionRequest : (new CompletionRequest($this->messages))
                    ->setStream(true)
                    ->setTemperature($temperature)
            ),
            stop : $stop
        );
    }
}
