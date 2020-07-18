<?php

namespace Quantic\Atom\Shell\Universes;

use Quantic\Atom\Shell\Console;

class Expand
{
    public static function Expand($force = false)
    {
        if ($force == true) {

            Console::$terminate = 'test';

        } else {

            Console::$terminate = 'test';
        }
    }

    public static function Rollback($step = false)
    {
        if ($step != false) {

            Console::$terminate = 'test';

        } else {

            Console::$terminate = 'test';
        }
    }

    public static function Reset()
    {
        Console::$terminate = 'test';
    }

    public static function Refresh($seed = false)
    {
        if ($seed != false) {

            Console::$terminate = 'test';

        } else {

            Console::$terminate = 'test';
        }
    }

    public static function Fresh($seed = false)
    {
        if ($seed != false) {

            Console::$terminate = 'test';

        } else {

            Console::$terminate = 'test';
        }
    }
}