<?php

namespace Quantic\Atom\Shell\Universes;

use Quantic\Atom\Shell\Console;
use Wujunze\Colors;

class Expand
{
    public static function Expand($force = false)
    {
        $colors = new \Wujunze\Colors();

        if ($force == true) {

            $result = (true) ? $colors->getColoredString('>>> test', 'light_green', null) : $colors->getColoredString
            ('>>> test', 'red', null);
            Console::$terminate = $result;

        } else {

            $result = (true) ? $colors->getColoredString('>>> test', 'light_green', null) : $colors->getColoredString
            ('>>> test', 'red', null);
            Console::$terminate = $result;
        }
    }

    public static function Rollback($step = false)
    {
        $colors = new \Wujunze\Colors();

        if ($step != false) {

            $result = (true) ? $colors->getColoredString('>>> test', 'light_green', null) : $colors->getColoredString
            ('>>> test', 'red', null);
            Console::$terminate = $result;

        } else {

            $result = (true) ? $colors->getColoredString('>>> test', 'light_green', null) : $colors->getColoredString
            ('>>> test', 'red', null);
            Console::$terminate = $result;
        }
    }

    public static function Reset()
    {
        $colors = new \Wujunze\Colors();

        $result = (true) ? $colors->getColoredString('>>> test', 'light_green', null) : $colors->getColoredString('>>> test',
            'red', null);
        Console::$terminate = $result;
    }

    public static function Refresh($seed = false)
    {
        $colors = new \Wujunze\Colors();

        if ($seed != false) {

            $result = (true) ? $colors->getColoredString('>>> test', 'light_green', null) : $colors->getColoredString
            ('>>> test', 'red', null);
            Console::$terminate = $result;

        } else {

            $result = (true) ? $colors->getColoredString('>>> test', 'light_green', null) : $colors->getColoredString
            ('>>> test', 'red', null);
            Console::$terminate = $result;
        }
    }

    public static function Fresh($seed = false)
    {
        $colors = new \Wujunze\Colors();

        if ($seed != false) {

            $result = (true) ? $colors->getColoredString('>>> test', 'light_green', null) : $colors->getColoredString
            ('>>> test', 'red', null);
            Console::$terminate = $result;

        } else {

            $result = (true) ? $colors->getColoredString('>>> test', 'light_green', null) : $colors->getColoredString
            ('>>> test', 'red', null);
            Console::$terminate = $result;
        }
    }
}