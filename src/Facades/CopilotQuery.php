<?php

namespace Elsayed85\CopilotQuery\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Elsayed85\CopilotQuery\CopilotQuery
 */
class CopilotQuery extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Elsayed85\CopilotQuery\CopilotQuery::class;
    }
}
