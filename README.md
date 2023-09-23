# Laravel Copilot Database Assistant

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elsayed85/copilot-ask-database.svg?style=flat-square)](https://packagist.org/packages/elsayed85/copilot-ask-database)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/elsayed85/copilot-ask-database/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/elsayed85/copilot-ask-database/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/elsayed85/copilot-ask-database/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/elsayed85/copilot-ask-database/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/elsayed85/copilot-ask-database.svg?style=flat-square)](https://packagist.org/packages/elsayed85/copilot-ask-database)

## Installation

You can install the package via composer:

```bash
composer require elsayed85/copilot-ask-database "@dev-master"
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="copilot-ask-database-config"
```

This is the contents of the published config file:

```php
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
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="copilot-ask-database-views"
```

Views Contains Prompts templates , you can customize it as you want.

## Setup Github Copilot

1. Authenticate with Github Copilot using code :
```bash
php artisan copilot:github:auth
```

```text
Please visit the following URL and login with your Github account:
https://github.com/login/device
Please enter the following code in the Github Device Activation page:
Your Github auth code is: 0EFA-6762
```

2. Verify Github Copilot Authentication (After you login with your Github account and enter the code)
```bash
php artisan copilot:github:verify
```

```text
Your Github access token is: gho_6E8rRBDL.........................
Please add the following line to your .env file:
COPILOT_ASK_GITHUB_TOKEN=gho_6E8rRBDL.........................
```

3. Add Github Copilot Token to your .env file
```dotenv
COPILOT_ASK_GITHUB_TOKEN=gho_6E8rRBDL.........................
```

## Usage

### Ask For SQL Query

```php
use Illuminate\Support\Facades\DB;

$question = 'How many users are there?';

$query = DB::askCopilotForQuery($question);

```
Answer 
```sql
SELECT COUNT(*) FROM users
```

### Ask For Human Answer

```php
use Illuminate\Support\Facades\DB;

$question = 'How many users are there?';

$query = DB::askCopilot($question);

```
Answer
```text
There are 10 users.
```



## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Elsayed Kamal](https://github.com/elsayed85)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
