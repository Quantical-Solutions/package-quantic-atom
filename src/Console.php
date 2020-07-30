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
    public static string $terminate;

    private static function ListenShellCommands($argv, $argc)
    {
        self::$control = $argc;
        self::$method = (isset($argv[1])) ? (strpos($argv[1], ':') !== false) ? explode(':', $argv[1])[0] : $argv[1] : false;
        self::$subject = (isset($argv[1])) ? (strpos($argv[1], ':') !== false) ? explode(':', $argv[1])[1] : false : false;
        self::$arg = (isset($argv[2])) ? $argv[2] : false;
        self::$terminate = '';
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
        $translate = (!is_string($checkSyntax)) ? Expansion::StellarPlan($checkSyntax) : $checkSyntax;
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
        if (is_string($response) && $response != '') {
            echo $response;
        }
    }

    public static function TerminateCommand()
    {
        $end = (self::$terminate == '') ? null : self::$terminate . PHP_EOL;
        return $end;
    }
}