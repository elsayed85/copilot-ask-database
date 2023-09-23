<?php

namespace Elsayed85\CopilotQuery\Commands;

use Elsayed85\CopilotQuery\Copilot\Github;
use Illuminate\Console\Command;

class VerifyGithubAuth extends Command
{
    public $signature = 'copilot:github:verify';

    public $description = 'Verify a Github auth code for Copilot';

    public function handle(): int
    {
        $github_device_code = cache('github_device_code');

        if (empty($github_device_code)) {
            $this->error('Please run the following command first:');
            $this->info('php artisan copilot:github:auth');

            return 1;
        }

        $github = new Github();

        if (! $github->confirm($github_device_code)) {
            $this->error($github->getError());

            return 1;
        }

        $this->info('Your Github access token is: '.$github->getAccessToken());
        $this->info('Please add the following line to your .env file:');
        $this->info('COPILOT_ASK_GITHUB_TOKEN='.$github->getAccessToken());

        cache()->forget('github_device_code');

        return 0;
    }
}
