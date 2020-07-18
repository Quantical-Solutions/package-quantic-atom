<?php

namespace Quantic\Atom\Shell;

use Quantic\Atom\Shell\Expansion;
use Quantic\Atom\Shell\Singularities;

class Console
{
    private static string $method;
    private static string $subject;
    private static string $arg;
    private static int $control;

    private static function ListenShellCommands($argv, $argc)
    {
        self::$control = $argc;
        self::$method = (isset($argv[1])) ? (strpos($argv[1], ':') !== false) ? explode(':', $argv[1])[0] : $argv[1] : false;
        self::$subject = (isset($argv[1])) ? (strpos($argv[1], ':') !== false) ? explode(':', $argv[1])[1] : false : false;
        self::$arg = (isset($argv[2])) ? $argv[2] : false;
    }

    private static function TranslateCommand()
    {
        $commands = [
            'method' => self::$method,
            'subject' => self::$subject,
            'arg' => self::$arg,
            'control' => self::$control
        ];

        $checkSyntax = Singularities::ScopeFixer($commands);
        $translate = (!is_string($checkSyntax)) ? Expansion::StellarPlan($commands) : $checkSyntax;
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

    public static function StatusCommand($response)
    {
        echo 'Status' . PHP_EOL;
    }

    public static function TerminateCommand($status)
    {
        echo 'Terminate' . PHP_EOL;
    }
}