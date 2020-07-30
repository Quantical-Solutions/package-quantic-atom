<?php

namespace Quantic\Atom\Shell;
use Wujunze\Colors;

class Singularities
{
    private static array $constructors = [

        // Files creation
        'create' => [
            'create:controller', // Create Controller file | {string}
            'create:model', // Create Model file | {string}
            'create:migration' // Create Migration file | {string}
        ],

        // JSX builder
        'concrete' => [
            'concrete:build', // Create JSX file | {string}
            'concrete:update', // Update JSX file | {string}
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
        $colors = new \Wujunze\Colors();

        $createError = 'php atom create [:controller {string}] [:model {string}] [:migration {string}]';
        $concreteError = 'php atom concrete [:build {string}] [:update {string}]';
        $modelError = 'php atom expand [ ] [:rollback [ ] [--step={int}]] [:reset] [:refresh [ ] [--seed]] [:fresh [ ] [--seed]]';
        $migrationError = 'php atom db [:seed [ ] [--class={string}] [--force]]';

        $result = $colors->getColoredString('ERROR : This method doesn\'t exist !', 'red', null) . PHP_EOL
            . $colors->getColoredString('Atom methods list :', 'cyan', null) . PHP_EOL . PHP_EOL
            . $colors->getColoredString('- ' . $createError, 'cyan', null) . PHP_EOL
            . $colors->getColoredString('- ' . $modelError, 'cyan', null) . PHP_EOL
            . $colors->getColoredString('- ' . $migrationError, 'cyan', null) . PHP_EOL;

        switch ($method) {

            case 'create':
                $result = self::createControl($subject, $arg, $control, $createError);
                break;

            case 'concrete':
                $result = self::concreteControl($subject, $arg, $control, $concreteError);
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
        $colors = new \Wujunze\Colors();

        $mark = $colors->getColoredString('ERROR : Bad create\'s function called or wrong syntax !', 'red', null) .
            PHP_EOL .  $colors->getColoredString('You mean : ', 'cyan', null) . PHP_EOL .
            $colors->getColoredString($errorMessage, 'light_purple', null) . PHP_EOL;

        if ($subject !== false && $arg !== false) {

            if ($control < 3 || $control > 3) {

                $mark = $colors->getColoredString('ERROR : CREATE method needs only 1 argument...', 'red', null) .
                    PHP_EOL;

            } else {

                $mark = self::formatControl('create', $reference, $subject, $arg, $control);
            }
        }

        return $mark;
    }

    private static function concreteControl($subject, $arg, $control, $errorMessage)
    {
        $reference = self::$constructors['concrete'];
        $colors = new \Wujunze\Colors();

        $mark = $colors->getColoredString('ERROR : Bad concrete\'s function called or wrong syntax !', 'red', null) .
            PHP_EOL .  $colors->getColoredString('You mean : ', 'cyan', null) . PHP_EOL .
            $colors->getColoredString($errorMessage, 'light_purple', null) . PHP_EOL;

        if ($subject !== false && $arg !== false) {

            if ($control < 3 || $control > 3) {

                $mark = $colors->getColoredString('ERROR : CONCRETE method needs only 1 argument...', 'red', null) .
                    PHP_EOL;

            } else {

                $mark = self::formatControl('concrete', $reference, $subject, $arg, $control);
            }
        }

        return $mark;
    }

    private static function expandControl($subject, $arg, $control, $errorMessage)
    {
        $reference = self::$constructors['expand'];
        $colors = new \Wujunze\Colors();

        $mark = $colors->getColoredString('ERROR : Bad expand\'s function called or wrong syntax !', 'red', null) .
            PHP_EOL . $colors->getColoredString( 'You mean : ', 'cyan', null) . PHP_EOL .
            $colors->getColoredString($errorMessage, 'light_purple', null) . PHP_EOL;

        if ($subject !== false && $arg !== false) {

            if ($control < 2 || $control > 3) {

                $mark = $colors->getColoredString('ERROR : EXPAND method needs at least 0 argument and at most 1...',
                        'red', null) . PHP_EOL;

            } else {

                $mark = self::formatControl('expand', $reference, $subject, $arg, $control);
            }
        }

        return $mark;
    }

    private static function dbControl($subject, $arg, $control, $errorMessage)
    {
        $reference = self::$constructors['db'];
        $colors = new \Wujunze\Colors();

        $mark = $colors->getColoredString('ERROR : Bad db\'s function called or wrong syntax !', 'red', null) .
            PHP_EOL . $colors->getColoredString('You mean : ', 'cyan', null) . PHP_EOL . $colors->getColoredString
            ($errorMessage, 'light_purple', null) . PHP_EOL;

        if ($subject !== false && $arg !== false) {

            if ($control < 2 || $control > 3) {

                $mark = $colors->getColoredString('ERROR : DB method needs at least 0 argument, at most 1...', 'red',
                        null) . PHP_EOL;

            } else {

                $mark = self::formatControl('db', $reference, $subject, $arg, $control);
            }
        }

        return $mark;
    }

    private static function formatControl($method, $reference, $subject, $arg, $control)
    {
        $colors = new \Wujunze\Colors();
        $arg1 = ($subject != false) ? ':' . $subject : '';
        $arg2 = ($arg != false) ? ' ' . $arg : '';

        switch ($method) {

            case 'create':

                $missing = ($subject != false) ? $subject : $arg;
                $mark = $colors->getColoredString('ERROR : The CREATE method\'s function "' . $missing . '" doesn\'t exist !', 'red', null) .  PHP_EOL . 'CREATE functions list :' . PHP_EOL;
                $mark .= $colors->getColoredString('[:controller {string}] [:model {string}] [:migration {string}]', 'light_purple', null)
                    . PHP_EOL;
                break;

            case 'concrete':

                $missing = ($subject != false) ? $subject : $arg;
                $mark = $colors->getColoredString('ERROR : The CONCRETE method\'s function "' . $missing . '" doesn\'t exist !', 'red', null) .  PHP_EOL . 'CONCRETE functions list :' . PHP_EOL;
                $mark .= $colors->getColoredString('[:build {string}] [:update {string}]', 'light_purple', null)
                    . PHP_EOL;
                break;

            case 'expand':

                $missing = ($subject != false) ? $subject : $arg;
                $mark = $colors->getColoredString('ERROR : The EXPAND method\'s function "' . $missing . '" doesn\'t exist !', 'red', null) . PHP_EOL . $colors->getColoredString('EXPAND functions list :', 'cyan', null) . PHP_EOL;
                $mark .= $colors->getColoredString('[ ] [:rollback [ ] [--step={int}]] [:reset] [:refresh [ ] [--seed]] [:fresh [ ] [--seed]]', 'light_purple', null) . PHP_EOL;
                break;

            case 'db':

                $missing = ($subject != false) ? $subject : $arg;
                $mark = $colors->getColoredString('ERROR : The DB method\'s function "' . $missing . '" doesn\'t exist !', 'red', null) .
                    PHP_EOL . $colors->getColoredString('DB functions list :', 'cyan', null) . PHP_EOL;
                $mark .= $colors->getColoredString('[:seed [ ] [--class={string}] [--force]]', 'light_purple', null) . PHP_EOL;
                break;
        }

        if ($method == 'create') {

            $verbose = $method . $arg1;

            if (in_array($verbose, $reference)) {

                if (!is_numeric(trim($arg2))) {

                    $mark = [
                        'method' => ucwords($method),
                        'job' => ucwords(str_replace(':', '', $arg1)),
                        'argument' => '',
                        'variable' => trim($arg2)
                    ];

                } else {

                    $mark = $colors->getColoredString('ERROR : ' . strtoupper($method) . ' variable must be a "String" type.', 'red', null) . PHP_EOL;
                }
            }

        } else if ($method == 'concrete') {

        $verbose = $method . $arg1;

        if (in_array($verbose, $reference)) {

            if (!is_numeric(trim($arg2))) {

                $mark = [
                    'method' => ucwords($method),
                    'job' => ucwords(str_replace(':', '', $arg1)),
                    'argument' => '',
                    'variable' => trim($arg2)
                ];

            } else {

                $mark = $colors->getColoredString('ERROR : ' . strtoupper($method) . ' variable must be a "String" type.', 'red', null) . PHP_EOL;
            }
        }

    } else if ($method == 'expand') {

            $argument = (count(explode('=', $arg2)) == 2) ? explode('=', $arg2)[0] . '=' : $arg2;
            $var = (isset(explode('=', $arg2)[1])) ? explode('=', $arg2)[1] : '';
            $verbose = $method . $arg1 . $argument;

            if (in_array($verbose, $reference)) {

                if ($argument == ' --step=' && $var != '' && is_numeric(trim($var))) {

                    $mark = [
                        'method' => ucwords($method),
                        'job' => ucwords(str_replace(':', '', $arg1)),
                        'argument' => trim($argument),
                        'variable' => trim($var)
                    ];

                } else if ($argument == ' --step=' && (!is_numeric(trim($var)) || $var == '')) {

                    $mark = $colors->getColoredString('ERROR : ' . strtoupper($method) . ' variable must be a "Int" type.', 'red', null) . PHP_EOL;

                } else if ($argument == ' --force' || $argument == ' --seed') {

                    $mark = [
                        'method' => ucwords($method),
                        'job' => ucwords(str_replace(':', '', $arg1)),
                        'argument' => trim($argument),
                        'variable' => trim($var)
                    ];

                } else if ($argument == '') {

                    $mark = [
                        'method' => ucwords($method),
                        'job' => ucwords(str_replace(':', '', $arg1)),
                        'argument' => trim($argument),
                        'variable' => trim($var)
                    ];
                }
            }

        } else if ($method == 'db') {

            $argument = (count(explode('=', $arg2)) == 2) ? explode('=', $arg2)[0] . '=' : $arg2;
            $var = (isset(explode('=', $arg2)[1])) ? explode('=', $arg2)[1] : '';
            $verbose = $method . $arg1 . $argument;

            if (in_array($verbose, $reference)) {

                if ($argument == ' --class=' && $var != '' && !is_numeric(trim($var))) {

                    $mark = [
                        'method' => ucwords($method),
                        'job' => ucwords(str_replace(':', '', $arg1)),
                        'argument' => trim($argument),
                        'variable' => trim($var)
                    ];

                } else if ($argument == ' --class=' && (is_numeric(trim($var)) || $var == '')) {

                    $mark = $colors->getColoredString('ERROR : ' . strtoupper($method) . ' variable must be a "String" type.', 'red', null) . PHP_EOL;

                } else if ($argument == ' --force') {

                    $mark = [
                        'method' => ucwords($method),
                        'job' => ucwords(str_replace(':', '', $arg1)),
                        'argument' => trim($argument),
                        'variable' => trim($var)
                    ];
                } else if ($argument == '') {

                    $mark = [
                        'method' => ucwords($method),
                        'job' => ucwords(str_replace(':', '', $arg1)),
                        'argument' => trim($argument),
                        'variable' => trim($var)
                    ];
                }
            }
        }

        return $mark;
    }
}