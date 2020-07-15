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
        self::$control = $argc;

        self::$method = '';
        self::$subject = '';
        self::$arg = '';
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

    public static function ExecuteCommand()
    {
        $argv = $_SERVER['argv'];
        $argc = $_SERVER['argc'];
        self::ListenShellCommands($argv, $argc);
        $response = self::TranslateCommand();
        return $response;
    }
}