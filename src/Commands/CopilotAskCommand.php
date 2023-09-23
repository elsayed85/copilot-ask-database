<?php

namespace Elsayed85\CopilotQuery\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\text;

class CopilotAskCommand extends Command
{
    public $signature = 'copilot:ask';

    public $description = 'Ask Copilot a question , Returns a A response';

    public function handle(): int
    {
        $question = text('What is your Question?');

        $answer = DB::askCopilot($question);

        $this->info($answer);

        return 0;
    }
}
