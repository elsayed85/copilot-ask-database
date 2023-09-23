<?php

return [
    'github_token' => env('COPILOT_ASK_GITHUB_TOKEN'),

    /**
     * The database connection name to use. Depending on your
     * use case, you might want to limit the database user
     * to have read-only access to the database.
     */
    'connection' => env('COPILOT_ASK_CONNECTION', 'mysql'),

    /**
     * Strict mode will throw an exception when the query
     * would perform a write/alter operation on the database.
     *
     * If you want to allow write operations - or if you are using a read-only
     * database user - you may disable strict mode.
     */
    'strict_mode' => env('COPILOT_ASK_STRICT_MODE', true),

    /**
     * The maximum number of tables to use before performing an additional
     * table name lookup call to OpenAI.
     * If you have a lot of database tables and columns, they might not fit
     * into a single request to OpenAI. In that case, we will perform a
     * lookup call to OpenAI to get the matching table names for the
     * provided question.
     */
    'max_tables_before_performing_lookup' => env('COPILOT_ASK_MAXIMUM_TABLES', 15),

    'copilot' => [
        'intent' => false,
        'model' => 'copilot-chat', // Don't change this
        'top_p' => 1,
        'n' => 1,

        'client_id' => '01ab8ac9400c4e429b23', // Don't change this
        'user_agent' => 'GithubCopilot/3.99.99', // Don't change this
    ],
];
