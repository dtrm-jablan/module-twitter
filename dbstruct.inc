<?php
/**
 * Twitter Integration Module
 *
 * @var array  $Module
 * @var string $mod
 * @var array  $appconf
 * @var array  $OverLoad
 */
use Determine\Module\Twitter\Module;

$Module[$mod]['menu'] = 1;
$Module[$mod]['data'] = 1;
$Module[$mod]['widget'] = 1;

$DbStruct['TWITTERID'] = [
    'table'   => 'TWITTER_FEED',
    'pref'    => 'TWITTERFEED',
    'data'    => 'ref',
    'trace'   => 1,
    'recname' => 'LABEL',
    'user'    => 'INITUSERID',
    'filter'  => 0x300,
    'fcb'     => 1,
    'path'    => $ModPath,
    'icon'    => 'm_twitterid.png',
    'seq'     => 1,
    'del'     => 1,
    'form'    => [],
    'lang'    => [],
    'lib'     => [],
    'rights'  => [
        'insert' => 'admin+corp',
        'select' => 'admin+corp',
        'list'   => 'admin+corp',
        'modif'  => 'admin+corp',
        'view'   => 'use',
        'copy'   => 'admin+corp',
        'export' => 'admin+corp',
    ],
];

//******************************************************************************
//* Overloads
//******************************************************************************

$OverLoad['DASHBOARD_HOMEID']['lang'][] = array('path' => $ModPath);
