<?php

namespace Quantic\Atom\Shell;

use Quantic\Atom\Shell\Universes\Create;
use Quantic\Atom\Shell\Universes\Expand;
use Quantic\Atom\Shell\Universes\Db;
use Quantic\Atom\Shell\Universes\Concrete;
use Wujunze\Colors;

class Expansion
{
    public static function StellarPlan($commands)
    {
        $job = $commands['job'];
        $arg = $commands['argument'];
        $var = $commands['variable'];
        $todo = false;
        $colors = new \Wujunze\Colors();

        switch ($commands['method']) {

            case 'Create':

                if ($job == 'Controller') {

                    $todo = $colors->getColoredString('>>> Processing to create "' . $var . '" controller file...', 'yellow', 'transparent');
                    Create::Controller($var);

                } else if ($job == 'Model') {

                    $todo = $colors->getColoredString('>>> Processing to create "' . $var . '" model file...', 'yellow', 'transparent');
                    Create::Model($var);

                } else if ($job == 'Migration') {

                    $file = strtolower(date("Ymd_His_") . $var);
                    if (self::ControlModelFormat(strtolower($var))) {
                        $todo = $colors->getColoredString('>>> Processing to create "' . $file . '" migration file...',
                            'yellow', 'transparent');
                        Create::Migration($file);
                    } else {
                        $todo = $colors->getColoredString('>>> Failed to create "' . $file . '" migration file because of wrong string format...', 'red', 'transparent');
                    }
                }
                break;

            case 'Concrete':

                if ($job == 'Build') {

                    if (self::controlViewExistence($var, 'build')) {
                        $todo = $colors->getColoredString('>>> Processing to build "' . $var . '.jsx" file...', 'yellow', 'transparent');
                        Concrete::Build($var);
                    } else {
                        $todo = $colors->getColoredString('>>> ERROR : file "' . $var . '.blade.php" doesn\'t exist...', 'red', 'transparent');
                    }

                } else if ($job == 'Update') {

                    if (self::controlViewExistence($var, 'update')) {
                        $todo = $colors->getColoredString('>>> Processing to update "' . $var . '.jsx" file...', 'yellow', 'transparent');
                        Concrete::Update($var);
                    } else {
                        $todo = $colors->getColoredString('>>> ERROR : file "' . $var . '.blade.php" and/or "' . $var . '.jsx" doesn\'t exist...', 'red', 'transparent');
                    }
                }
                break;

            case 'Expand':

                if ($job == '') {

                    $argu = ($arg == '') ? false : $arg;
                    $forced = ($arg == '--force') ? 'forced' : '';
                    $todo = $colors->getColoredString('>>> Processing ' . $forced . ' expansion...', 'yellow', 'transparent');
                    Expand::Expand($argu);

                } else if ($job == 'Rollback') {

                    $argu = ($var == '') ? false : $var;
                    $step = ($var == '') ? '' : ' ' . $var . ' step(s) forward';
                    $todo = $colors->getColoredString('>>> Processing rolling back' . $step . ' expansion...', 'yellow', 'transparent');
                    Expand::Rollback($argu);

                } else if ($job == 'Reset') {

                    $todo = $colors->getColoredString('>>> Processing resting expansion...', 'yellow', 'transparent');
                    Expand::Reset();

                } else if ($job == 'Refresh') {

                    $argu = ($arg == '') ? false : $arg;
                    $seed = ($arg == '') ? '' : ' and seed';
                    $todo = $colors->getColoredString('>>> Processing refreshing expansion' . $seed . '...', 'yellow', 'transparent');
                    Expand::Refresh($argu);

                } else if ($job == 'Fresh') {

                    $argu = ($arg == '') ? false : $arg;
                    $seed = ($arg == '') ? '' : ' and seed';
                    $todo = $colors->getColoredString('>>> Processing freshing expansion' . $seed . '...', 'yellow', 'transparent');
                    Expand::Fresh($argu);
                }
                break;

            case 'Db':

                if ($job == 'Seed' && $arg == '') {

                    $todo = $colors->getColoredString('>>> Seeding in progress...', 'yellow', 'transparent');
                    Db::Seed(false, false);

                } else if ($job == 'Seed' && $arg == '--class=' && $var!= '') {

                    $todo = $colors->getColoredString('>>> Seeding class "' . $var . '" in progress...', 'yellow', 'transparent');
                    Db::Seed($var, false);

                } else if ($job == 'Seed' && $arg == '--force') {

                    $todo = $colors->getColoredString('>>> Forced seeding in progress...', 'yellow', 'transparent');
                    Db::Seed(false, true);
                }
                break;
        }

        return $todo . PHP_EOL;
    }

    public static function ControlModelFormat($var)
    {
        $response = false;
        $explode = explode('_', $var);

        if (substr($var, 0, 7) == 'create_') {
            if ($explode[count($explode)-1] == 'table') {
                $response = true;
            }
        } else if (substr($var, 0, 12) == 'add_columns_') {
            if ($explode[count($explode)-2] == 'to') {
                $response = true;
            }
        }
        return $response;
    }

    public static function controlViewExistence($var, $type)
    {
        $response = false;
        $views = ROOTDIR . '/resources/views/';
        $jsx = ROOTDIR . '/app/React/';

        if ($type == 'build') {

            if (file_exists($views . $var . '.blade.php') && !file_exists($jsx . $var . '.jsx')) {
                $response = true;
            }

        } else if ($type == 'update') {

            if (file_exists($views . $var . '.blade.php') && file_exists($jsx . $var . '.jsx')) {
                $response = true;
            }
        }

        return $response;
    }
}