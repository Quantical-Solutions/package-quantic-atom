<?php

namespace Quantic\Atom\Shell;

class Singularities
{
    private static array $constructors = [

        // Files creation
        'create' => [
            'controller',
            'model',
            'migration'
        ],

        // DB migration
        'expand' => [
            '',
            '--force',
            'rollback' => [
                '',
                '--step=?'
            ],
            'reset',
            'refresh' => [
                '',
                '--seed'
            ],
            'fresh' => [
                '',
                '--seed'
            ]
        ],

        // DB populate
        'db' => [
            'seed' => [
                '',
                '--class=?',
                '--force'
            ]
        ]
    ];

    public static function ScopeFixer($data)
    {
        $method = $data['method'];
        $subject = $data['subject'];
        $arg = $data['arg'];
        $control = $data['control'];

        $result = 'This method doesn\'t exist' . PHP_EOL
            . 'Atom methods list :' . PHP_EOL
            . 'create [:controller {string}] [:model {string}] [:migration {string}]' . PHP_EOL
            . 'expand [ ] [:rollback [ ] [--step={int}]] [:reset] [:refresh [ ] [--seed]] [:fresh [ ] [--seed]]' . PHP_EOL
            . 'db [:seed [ ] [--class={string}] [--force]]' . PHP_EOL;

        switch ($method) {

            case 'create':
                $result = (self::makeControl($subject, $arg, $control)) ? true : $result;
                break;

            case 'expand':
                $result = (self::migrateControl($subject, $arg, $control)) ? true : $result;
                break;

            case 'db':
                $result = (self::dbControl($subject, $arg, $control)) ? true : $result;
                break;
        }
        return $result;
    }

    private function makeControl($subject, $arg, $control)
    {
        $reference = self::$constructors['create'];
        $mark = false;
        if ($subject !== false && $arg !== false && $control == 3) {
            $mark = self::formatControl('create', $reference, $subject, $arg, $control);
        }
        return $mark;
    }

    private function migrateControl($subject, $arg, $control)
    {
        $reference = self::$constructors['expand'];
        $mark = false;
        if ($subject !== false && $arg !== false && ($control == 2 || $control == 3)) {
            $mark = self::formatControl('expand', $reference, $subject, $arg, $control);
        }
        return $mark;
    }

    private function dbControl($subject, $arg, $control)
    {
        $reference = self::$constructors['db'];
        $mark = false;
        if ($subject !== false && $arg !== false && ($control == 2 || $control == 3)) {
            $mark = self::formatControl('db', $reference, $subject, $arg, $control);
        }
        return $mark;
    }

    private function formatControl($method, $reference, $subject, $arg, $control)
    {
        $mark = 'The method ' . strtoupper($method) . ' "' . $subject . '" does\'t exist' . PHP_EOL . strtoupper($method)
            . ' functions list :' . PHP_EOL;

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

        foreach ($reference as $key => $ref) {

            if (is_array($ref)) {

            } else {

            }
            echo $key . ' ' . $ref;
        }

        return $mark;
    }
}