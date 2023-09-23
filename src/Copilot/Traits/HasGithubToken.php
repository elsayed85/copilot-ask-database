<?php

namespace Elsayed85\CopilotQuery\Copilot\Traits;

trait HasGithubToken
{
    private string $githubToken;

    private function setGithubToken($token): static
    {
        $this->githubToken = $token;

        return $this;
    }

    private function getGithubToken(): ?string
    {
        return $this->githubToken;
    }
}
