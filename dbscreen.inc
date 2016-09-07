<?php
/**
 * Twitter Integration Module
 *
 * @var array $DbScreen
 */

use Determine\Library\Utility\Enums\LoadModes;

$DbScreen['TWITTERID_DISP'] = [
    'load'     => 'TWITTERID',
    'loadmode' => LoadModes::COPY_FILES,
    'rights'   => [
        'insert' => 'none',
        'select' => 'none',
        'list'   => 'none',
        'modif'  => 'none',
        'view'   => 'use',
        'copy'   => 'none',
        'export' => 'none',
    ],
];
