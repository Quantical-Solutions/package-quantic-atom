<?php

namespace Quantic\Atom\Shell;

class Singularities
{
    private static array $constructors = [

        // Files creation
        'create' => [
            'create:controller', // Create Controller file | {string}
            'create:model', // Create Model file | {string}
            'create:migration' // Create Migration file | {string}
        ],

        // DB migration
        'expand' => [
            'expand', // Migrate all with "if not exist"
            'expand --force', // Migrate all forced with "Replace all"
            'expand:rollback', // Get last migration state
            'expand:rollback --step=', // Get n previous migration | n={int}
            'expand:reset', // Truncate Migration table
            'expand:refresh', // Refresh Tables Structure
            'expand:refresh --seed', // Refresh Tables structures and populate
            'expand:fresh', // Truncate tables
            'expand:fresh --seed' // Truncate tables and populate
        ],

        // DB populate
        'db' => [
            'db:seed', // Populate Database with control "if row not exists"
            'db:seed --class=', // Populate a targeted table with a migration file | class={string}
            'db:seed --force' // Update tables "if row exists" and Insert "if row doesn't exist"
        ]
    ];

    public static function ScopeFixer($data)
    {
        $method = $data['method'];
        $subject = $data['subject'];
        $arg = $data['arg'];
        $control = $data['control'];

        $createError = 'php atom create [:controller {string}] [:model {string}] [:migration {string}]';
        $modelError = 'php atom expand [ ] [:rollback [ ] [--step={int}]] [:reset] [:refresh [ ] [--seed]] [:fresh [ ] [--seed]]';
        $migrationError = 'php atom db [:seed [ ] [--class={string}] [--force]]';

        $result = 'ERROR : This method doesn\'t exist !' . PHP_EOL
            . 'Atom methods list :' . PHP_EOL . PHP_EOL
            . '- ' . $createError . PHP_EOL
            . '- ' . $modelError . PHP_EOL
            . '- ' . $migrationError . PHP_EOL;

        switch ($method) {

            case 'create':
                $result = self::createControl($subject, $arg, $control, $createError);
                break;

            case 'expand':
                $result = self::expandControl($subject, $arg, $control, $modelError);
                break;

            case 'db':
                $result = self::dbControl($subject, $arg, $control, $migrationError);
                break;
        }
        return $result;
    }

    private static function createControl($subject, $arg, $control, $errorMessage)
    {
        $reference = self::$constructors['create'];
        $mark = 'ERROR : Bad create\'s function called or wrong syntax !' . PHP_EOL .  'You mean : ' . PHP_EOL .
            $errorMessage . PHP_EOL;

        if ($subject !== false && $arg !== false) {

            if ($control < 3 || $control > 3) {

                $mark = 'ERROR : CREATE method needs only 1 argument...' . PHP_EOL;

            } else {

                $mark = self::formatControl('create', $reference, $subject, $arg, $control);
            }
        }

        return $mark;
    }

    private static function expandControl($subject, $arg, $control, $errorMessage)
    {
        $reference = self::$constructors['expand'];
        $mark = 'ERROR : Bad expand\'s function called or wrong syntax !' . PHP_EOL .  'You mean : ' . PHP_EOL .
            $errorMessage . PHP_EOL;

        if ($subject !== false && $arg !== false) {

            if ($control < 2 || $control > 3) {

                $mark = 'ERROR : EXPAND method needs at least 0 argument and at most 1...' . PHP_EOL;

            } else {

                $mark = self::formatControl('expand', $reference, $subject, $arg, $control);
            }
        }

        return $mark;
    }

    private static function dbControl($subject, $arg, $control, $errorMessage)
    {
        $reference = self::$constructors['db'];
        $mark = 'ERROR : Bad db\'s function called or wrong syntax !' . PHP_EOL . 'You mean : ' . PHP_EOL . $errorMessage . PHP_EOL;

        if ($subject !== false && $arg !== false) {

            if ($control < 2 || $control > 3) {

                $mark = 'ERROR : DB method needs at least 0 argument, at most 1...' . PHP_EOL;

            } else {

                $mark = self::formatControl('db', $reference, $subject, $arg, $control);
            }
        }

        return $mark;
    }

    private static function formatControl($method, $reference, $subject, $arg, $control)
    {
        $arg1 = ($subject != false) ? ':' . $subject : '';
        $arg2 = ($arg != false) ? ' ' . $arg : '';

        switch ($method) {

            case 'create':

                $missing = ($subject != false) ? $subject : $arg;
                $mark = 'ERROR : The CREATE method\'s function "' . $missing . '" doesn\'t exist !' .
                    PHP_EOL . 'CREATE functions list :' . PHP_EOL;
                $mark .= '[:controller {string}] [:model {string}] [:migration {string}]' . PHP_EOL;
                break;

            case 'expand':

                $missing = ($subject != false) ? $subject : $arg;
                $mark = 'ERROR : The EXPAND method\'s function "' . $missing . '" doesn\'t exist !' .
                    PHP_EOL . 'EXPAND functions list :' . PHP_EOL;
                $mark .= '[ ] [:rollback [ ] [--step={int}]] [:reset] [:refresh [ ] [--seed]] [:fresh [ ] [--seed]]' . PHP_EOL;
                break;

            case 'db':

                $missing = ($subject != false) ? $subject : $arg;
                $mark = 'ERROR : The DB method\'s function "' . $missing . '" doesn\'t exist !' .
                    PHP_EOL . 'DB functions list :' . PHP_EOL;
                $mark .= '[:seed [ ] [--class={string}] [--force]]' . PHP_EOL;
                break;
        }

        if ($method == 'create') {

            $verbose = $method . $arg1;

            if (in_array($verbose, $reference)) {

                if (!is_numeric(trim($arg2))) {
                    $mark = true;
                } else {
                    $mark = 'ERROR : ' . strtoupper($method) . ' variable must be a "String" type.' . PHP_EOL;
                }
            }

        } else if ($method == 'expand') {

            $argument = (count(explode('=', $arg2)) == 2) ? explode('=', $arg2)[0] . '=' : $arg2;
            $var = (isset(explode('=', $arg2)[1])) ? explode('=', $arg2)[1] : '';
            $verbose = $method . $arg1 . $argument;

            if (in_array($verbose, $reference)) {

                if ($argument == ' --step=' && is_numeric(trim($var))) {
                    $mark = true;
                } else if ($argument == ' --step=' && !is_numeric(trim($var))) {
                    $mark = 'ERROR : ' . strtoupper($method) . ' variable must be a "Int" type.' . PHP_EOL;
                } else if ($argument == ' --force' || $argument == ' --seed') {
                    $mark = true;
                } else if ($argument == '') {
                    $mark = true;
                }
            }

        } else if ($method == 'db') {

            $argument = (count(explode('=', $arg2)) == 2) ? explode('=', $arg2)[0] . '=' : $arg2;
            $var = (isset(explode('=', $arg2)[1])) ? explode('=', $arg2)[1] : '';
            $verbose = $method . $arg1 . $argument;

            if (in_array($verbose, $reference)) {

                if ($argument == ' --class=' && !is_numeric(trim($var))) {
                    $mark = true;
                } else if ($argument == ' --class=' && is_numeric(trim($var))) {
                    $mark = 'ERROR : ' . strtoupper($method) . ' variable must be a "String" type.' . PHP_EOL;
                } else if ($argument == ' --force') {
                    $mark = true;
                } else if ($argument == '') {
                    $mark = true;
                }
            }
        }

        return $mark;
    }
}