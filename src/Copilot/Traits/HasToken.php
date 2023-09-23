<?php

namespace Elsayed85\CopilotQuery\Copilot\Traits;

use Carbon\Carbon;
use Elsayed85\CopilotQuery\Exceptions\Copilot\TokenCreationException;
use Illuminate\Support\Facades\Http;

trait HasToken
{
    use HasGithubToken;

    private ?string $token;

    private $tokenExpiresAt;

    private function getToken(): ?string
    {
        return $this->token;
    }

    public function getTokenExpiresAt(): Carbon
    {
        return Carbon::parse($this->tokenExpiresAt);
    }

    /**
     * @throws TokenCreationException
     */
    private function generateToken(): array
    {
        $response = Http::withHeaders(['User-Agent' => config('copilot-ask-database.copilot.user_agent')])
            ->withToken($this->getGithubToken())
            ->get('https://api.github.com/copilot_internal/v2/token');

        if ($response->status() != 200) {
            throw new TokenCreationException('Could not generate copilot token', $response->status(), $response->json());
        }

        $response = $response->json();

        if (! isset($response['token']) || ! isset($response['expires_at'])) {
            throw new TokenCreationException('Could not generate copilot token', $response->status(), $response->json());
        }

        return $response;
    }

    private function shouldGenerateNewToken(): bool
    {
        if (! $this->token || ! $this->tokenExpiresAt) {
            return true;
        }

        return $this->getTokenExpiresAt()->isPast();
    }

    /**
     * @throws TokenCreationException
     */
    private function getOrRefreshToken(): void
    {
        $this->token = cache('copilot_token');
        $this->tokenExpiresAt = cache('copilot_token_expires_at');

        if ($this->shouldGenerateNewToken()) {
            $response = $this->generateToken();

            $this->token = $response['token'];
            $this->tokenExpiresAt = $response['expires_at'];

            cache()->put('copilot_token', $this->token, $this->tokenExpiresAt);
            cache()->put('copilot_token_expires_at', $this->tokenExpiresAt);
        }

    }
}
