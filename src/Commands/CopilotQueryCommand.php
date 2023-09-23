<?php

namespace Elsayed85\CopilotQuery\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\text;

class CopilotQueryCommand extends Command
{
    public $signature = 'copilot:query';

    public $description = 'Ask Copilot a question , Returns a query';

    public function handle(): int
    {
        $question = text('What is your Question?');

        $answer = DB::askCopilotForQuery($question);

        $this->info($answer);

        return 0;
    }
}
