<?php

namespace Quantic\Atom\Shell\Universes;

use Quantic\Atom\Shell\Console;
use Wujunze\Colors;

class Db
{
    public static function Seed($class = false, $force = false)
    {
        $colors = new \Wujunze\Colors();

        if ($class != false) {

            $result = (true) ? $colors->getColoredString('test', 'light_green', null) : $colors->getColoredString('test',
                'red', null);
            Console::$terminate = $result;

        } else if ($force == true) {

            $result = (true) ? $colors->getColoredString('test', 'light_green', null) : $colors->getColoredString('test',
                'red', null);
            Console::$terminate = $result;

        } else {

            $result = (true) ? $colors->getColoredString('test', 'light_green', null) : $colors->getColoredString('test',
                'red', null);
            Console::$terminate = $result;
        }
    }
}