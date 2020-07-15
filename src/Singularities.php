<?php

namespace Quantic\Atom\Shell;

class Singularities
{
    public static function ScopeFixer($data)
    {
        $constructors = [
            'make' => [
                ':controller',
                ':model',
                ':migration'
            ],
            'migrate' => [
                '' => [
                    '',
                    '--force'
                ],
                ':rollback' => [
                    '',
                    '--step=?'
                ],
                ':reset',
                ':refresh' => [
                    '',
                    '--seed'
                ],
                ':fresh' => [
                    '',
                    '--seed'
                ]
            ],
            'db' => [
                ':seed' => [
                    '--class=?',
                    '--force'
                ]
            ]
        ];

        return true;
    }
}