<?php

namespace Elsayed85\CopilotQuery;

use Elsayed85\CopilotQuery\Copilot\CopilotApi;
use Elsayed85\CopilotQuery\Exceptions\PotentiallyUnsafeQuery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CopilotQuery
{
    protected string $connection;

    public function __construct(protected CopilotApi $client)
    {
        $this->connection = config('copilot-ask-database.connection');
    }

    public function ask(string $question): string
    {
        $query = $this->getQuery($question);

        if (empty($query)) {
            return '';
        }

        $result = json_encode($this->evaluateQuery($query));
        $prompt = $this->buildPrompt($question, $query, $result);

        try {
            $answer = $this->queryCopilot(
                prompt: $prompt,
                temperature: 0.7
            );

            return Str::of($answer)
                ->trim()
                ->trim('"');
        } catch (\Exception $e) {
            return $result;
        }
    }

    public function getQuery(string $question): string
    {
        $prompt = $this->buildPrompt($question);

        try {
            $query = $this->queryCopilot(
                prompt: $prompt,
                stop: "\n"
            );

        } catch (\Exception $e) {
            return '';
        }

            $query = Str::of($query)
                ->trim()
                ->trim('"');

        $this->ensureQueryIsSafe($query);

        return $query;

    }

    /**
     * @throws \Exception
     */
    protected function queryCopilot(string $prompt, ?float $temperature = 0.0, ?string $stop = ''): string
    {
        return $this->client->addMessage($prompt)->send(stop: $stop, temperature: $temperature);
    }

    protected function buildPrompt(string $question, string $query = null, string $result = null): string
    {
        $tables = $this->getTables($question);

        $prompt = (string) view('copilot-ask-database::prompts.query', [
            'question' => $question,
            'tables' => $tables,
            'dialect' => $this->getDialect(),
            'query' => $query,
            'result' => $result,
        ]);

        return rtrim($prompt, PHP_EOL);
    }

    protected function evaluateQuery(string $query): object
    {
        return DB::connection($this->connection)->select($this->getRawQuery($query))[0] ?? new \stdClass();
    }

    protected function getRawQuery(string $query): string
    {
        if (version_compare(app()->version(), '10.0', '<')) {
            /* @phpstan-ignore-next-line */
            return (string) DB::raw($query);
        }

        return DB::raw($query)->getValue(DB::connection($this->connection)->getQueryGrammar());
    }

    /**
     * @throws PotentiallyUnsafeQuery
     */
    protected function ensureQueryIsSafe(string $query): void
    {
        if (! config('copilot-ask-database.strict_mode')) {
            return;
        }

        $query = strtolower($query);
        $forbiddenWords = ['insert', 'update ', 'delete ', 'alter', 'drop', 'truncate', 'create ', 'replace'];

        throw_if(Str::contains($query, $forbiddenWords), PotentiallyUnsafeQuery::fromQuery($query));
    }

    protected function getDialect(): string
    {
        $databasePlatform = DB::connection($this->connection)->getDoctrineConnection()->getDatabasePlatform();

        return Str::before(class_basename($databasePlatform), 'Platform');
    }

    /**
     * @return \Doctrine\DBAL\Schema\Table[]
     */
    protected function getTables(string $question): array
    {
        return once(function () use ($question) {
            $tables = DB::connection($this->connection)
                ->getDoctrineSchemaManager()
                ->listTables();

            if (count($tables) < config('copilot-ask-database.max_tables_before_performing_lookup')) {
                return $tables;
            }

            return $this->filterMatchingTables($question, $tables);
        });
    }

    protected function filterMatchingTables(string $question, array $tables): array
    {
        $prompt = (string) view('copilot-ask-database::prompts.tables', [
            'question' => $question,
            'tables' => $tables,
        ]);

        $prompt = rtrim($prompt, PHP_EOL);

        try {
            $matchingTablesResult = $this->queryCopilot(
                prompt: $prompt,
                stop: "\n",
            );

            $matchingTables = Str::of($matchingTablesResult)
                ->explode(',')
                ->transform(fn (string $tableName) => strtolower(trim($tableName)));

            return collect($tables)->filter(function ($table) use ($matchingTables) {
                return $matchingTables->contains(strtolower($table->getName()));
            })->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}
