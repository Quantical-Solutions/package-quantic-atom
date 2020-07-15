<?php

namespace Quantic\Atom\Shell;

use Quantic\Atom\Shell\Expansion;

class Console
{
    private static string $method;
    private static string $subject;
    private static string $arg;
    private static int $control;

    private static function ListenShellCommands($argv, $argc)
    {
        self::$method = '';
        self::$subject = '';
        self::$arg = '';
        self::$control = $argc;
    }


    private static function TranslateCommand()
    {
        $commands = [
            self::$method,
            self::$subject,
            self::$arg,
            self::$control
        ];

        $translate = Expansion::StellarPlan($commands);
        return $translate;
    }

    public static function ExecuteCommand($argv, $argc)
    {
        self::ListenShellCommands($argv, $argc);
        $response = self::TranslateCommand();
        return $response;
    }
}