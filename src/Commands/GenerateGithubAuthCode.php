<?php

namespace Elsayed85\CopilotQuery\Commands;

use Elsayed85\CopilotQuery\Copilot\Github;
use Illuminate\Console\Command;

class GenerateGithubAuthCode extends Command
{
    public $signature = 'copilot:github:auth';

    public $description = 'Generate a Github auth code for Copilot';

    public function handle(): int
    {
        $github = new Github();

        $this->info('Please visit the following URL and login with your Github account:');

        $this->info($github->getAuthUrl());

        $github->generateToken();

        $this->info('Please enter the following code in the Github Device Activation page:');

        $this->info('Your Github auth code is: '.$github->getUserCode());

        cache()->put('github_device_code', $github->getDeviceCode());

        return 0;
    }
}
