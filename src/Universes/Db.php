<?php

namespace Quantic\Atom\Shell\Universes;

use Quantic\Atom\Shell\Console;

class Db
{
    public static function Seed($class = false, $force = false)
    {
        if ($class != false) {

            Console::$terminate = 'test';

        } else if ($force == true) {

            Console::$terminate = 'test';

        } else {

            Console::$terminate = 'test';
        }
    }
}