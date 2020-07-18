<?php

namespace Quantic\Atom\Shell\Universes;

use Quantic\Atom\Shell\Console;

class Create
{
    public static function Controller($name)
    {
        Console::$terminate = 'test';
    }

    public static function Model($name)
    {
        Console::$terminate = 'test';
    }

    public static function Migration($name)
    {
        Console::$terminate = 'test';
    }
}