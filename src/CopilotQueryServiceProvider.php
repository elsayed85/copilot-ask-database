<?php

namespace Elsayed85\CopilotQuery;

use Elsayed85\CopilotQuery\Commands\CopilotAskCommand;
use Elsayed85\CopilotQuery\Commands\CopilotQueryCommand;
use Elsayed85\CopilotQuery\Commands\GenerateGithubAuthCode;
use Elsayed85\CopilotQuery\Commands\VerifyGithubAuth;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CopilotQueryServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('copilot-ask-database')
            ->hasConfigFile()
            ->hasViews('copilot-ask-database')
            ->hasCommands([
                GenerateGithubAuthCode::class,
                VerifyGithubAuth::class,
                CopilotQueryCommand::class,
                CopilotAskCommand::class,
            ]);
    }

    public function registeringPackage(): void
    {
        DB::macro('askCopilot', function (string $question) {
            return $this->app->make(CopilotQuery::class)->ask($question);
        });
        DB::macro('askCopilotForQuery', function (string $question) {
            return $this->app->make(CopilotQuery::class)->getQuery($question);
        });
    }
}
