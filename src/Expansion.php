<?php

namespace Quantic\Atom\Shell;

use Quantic\Atom\Shell\Universes\Create;
use Quantic\Atom\Shell\Universes\Expand;
use Quantic\Atom\Shell\Universes\Db;

class Expansion
{
    public static function StellarPlan($commands)
    {
        $job = $commands['job'];
        $arg = $commands['argument'];
        $var = $commands['variable'];
        $todo = false;
        //echo json_encode($commands) . PHP_EOL;

        switch ($commands['method']) {

            case 'Create':

                if ($job == 'Controller') {

                    Create::Controller($var);
                    $todo = '>>> Processing to create "' . ucwords($var) . '" controller file...';

                } else if ($job == 'Model') {

                    Create::Model($var);
                    $todo = '>>> Processing to create "' . ucwords($var) . '" model file...';

                } else if ($job == 'Migration') {

                    Create::Migration($var);
                    $todo = '>>> Processing to create "' . ucwords($var) . '" migration file...';
                }
                break;

            case 'Expand':

                if ($job == '') {

                    $argu = ($arg == '') ? false : $arg;
                    $forced = ($arg == '--force') ? 'forced' : '';
                    Expand::Expand($argu);
                    $todo = '>>> Processing ' . $forced . ' expansion...';

                } else if ($job == 'Rollback') {

                    $argu = ($var == '') ? false : $var;
                    $step = ($var == '') ? '' : ' ' . $var . ' step(s) forward';
                    Expand::Rollback($argu);
                    $todo = '>>> Processing rolling back' . $step . ' expansion...';

                } else if ($job == 'Reset') {

                    Expand::Reset();
                    $todo = '>>> Processing resting expansion...';

                } else if ($job == 'Refresh') {

                    $argu = ($arg == '') ? false : $arg;
                    $seed = ($arg == '') ? '' : ' and seed';
                    Expand::Refresh($argu);
                    $todo = '>>> Processing refreshing expansion' . $seed . '...';

                } else if ($job == 'Fresh') {

                    $argu = ($arg == '') ? false : $arg;
                    $seed = ($arg == '') ? '' : ' and seed';
                    Expand::Fresh($argu);
                    $todo = '>>> Processing freshing expansion' . $seed . '...';
                }
                break;

            case 'Db':

                if ($job == 'Seed' && $arg == '') {

                    Db::Seed(false, false);
                    $todo = '>>> Seeding in progress...';

                } else if ($job == 'Seed' && $arg == '--class=' && $var!= '') {

                    Db::Seed($var, false);
                    $todo = '>>> Seeding class "' . ucwords($var) . '" in progress...';

                } else if ($job == 'Seed' && $arg == '--force') {

                    Db::Seed(false, true);
                    $todo = '>>> Forced seeding in progress...';
                }
                break;
        }

        return $todo;
    }
}