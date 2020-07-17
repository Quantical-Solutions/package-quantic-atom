<?php

namespace Quantic\Atom\Shell;

class Singularities
{
    private static array $constructors = [

        // Files creation
        'create' => [
            'create:controller {string}', // Create Controller file
            'create:model {string}', // Create Model file
            'create:migration {string}' // Create Migration file
        ],

        // DB migration
        'expand' => [
            'expand', // Migrate all with "if not exist"
            'expand|--force', // Migrate all forced with "Replace all"
            'expand:rollback', // Get last migration state
            'expand:rollback|--step={int}', // Get {int}est previous migration
            'expand:reset', // Truncate Migration table
            'expand:refresh', // Refresh Tables Structure
            'expand:refresh|--seed', // Refresh Tables structures and populate
            'expand:fresh', // Truncate tables
            'expand:fresh|--seed' // Truncate tables and populate
        ],

        // DB populate
        'db' => [
            'db:seed', // Populate Database with control "if row not exists"
            'db:seed|--class={string}', // Populate a targeted table with a migration file
            'db:seed|--force' // Update tables "if row exists" and Insert "if row doesn't exist"
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
        $missing = ($subject != '') ? $subject : $arg;
        $mark = 'ERROR : The ' . strtoupper($method) . ' method\'s function "' . $missing . '" doesn\'t exist !' .
            PHP_EOL . strtoupper($method) . ' functions list :' . PHP_EOL;

        switch ($method) {

            case 'create':
                $mark .= '[:controller {string}] [:model {string}] [:migration {string}]' . PHP_EOL;
                break;

            case 'expand':
                $mark .= '[ ] [:rollback [ ] [--step={int}]] [:reset] [:refresh [ ] [--seed]] [:fresh [ ] [--seed]]' . PHP_EOL;
                break;

            case 'db':
                $mark .= '[:seed [ ] [--class={string}] [--force]]' . PHP_EOL;
                break;
        }

        $arg1 = ($subject != '') ? ':' . $subject : '';
        $arg2 = ($arg != '') ? $arg : '';
        $string = '';
        $int = '';

        echo $arg2 . PHP_EOL;

        if (count(explode('=', $arg2)) == 2) {

            $arg2 = explode('=', $arg2)[0] . ' ' . explode('=', $arg2)[1];
            $var = explode('=', $arg2)[1];
            $string = (!is_int(intval($var)));
            $int = (is_int(intval($var)));
        }

        $verbose = $method . $arg1 . $arg2;

        foreach ($reference as $ref) {

            $stringRef = (strpos($ref, '{string}') !== false) ? str_replace('{string}', '', $ref) : $ref;
            $intRef = (strpos($ref, '{int}') !== false) ? str_replace('{int}', '', $ref) : $ref;
            $verb = explode('=', $verbose)[0];

            echo $intRef . PHP_EOL;
            echo $verb . PHP_EOL;

            if ($string && $stringRef == $verb) {

                $mark = true;

            } else if ($string && $stringRef != $verb) {

                $mark = 'ERROR : ' . strtoupper($method) . ' variable must be a "String" type.' . PHP_EOL;

            } else if ($int && $intRef == $verb) {

                $mark = true;

            } else if ($int && $intRef != $verb) {

                $mark = 'ERROR : ' . strtoupper($method) . ' variable must be an "Int" type.' . PHP_EOL;

            } else if ($ref == $verbose) {

                $mark = true;
            }
        }

        return $mark;
    }
}