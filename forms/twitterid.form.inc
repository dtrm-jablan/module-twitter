<?php
/**
 * Twitter Integration Module
 *
 * @var array  $Module
 * @var array  $DbScreen
 * @var array  $DbStruct
 * @var array  $Rights
 * @var string $mod
 * @var array  $appconf
 * @var array  $OverLoad
 */

//******************************************************************************
//* Rights
//******************************************************************************

use Determine\Library\Utility\Enums\ViewLevels;

$Rights['viewstat'] = 'owner,admin';

//******************************************************************************
//* Properties
//******************************************************************************

$Attrib['cols'] = 1;
$Attrib['ivalid'] = 2;
$Attrib['iclose'] = 20;

if ($COREVERSION >= 151000) {
    $Attrib['client'] = 0x01;
    $Attrib['blockStyle'] = 'panel-default';
}

//******************************************************************************
//* Declarations
//******************************************************************************

//  DEF block
$Var['TWITTERDEFBLOC'] = array('Sep' => 'line', 'NbCols' => 1, 'Style' => 'block-default');

$Var['LABEL'] = array(
    'Type'    => 'char',
    'Input'   => 'text',
    'Size'    => 80,
    'MaxSize' => 240,
    'View'    => 0,
    'Format'  => ',',
    'NbCols'  => 1,
    'Search'  => ['Type' => 'string'],
    'Key'     => 1,
);

$Var['STARTDATE'] = array('Load' => 'DATE');
$Var['ENDDATE'] = array('Load' => 'DATE');

//  EDITION block
$Var['TWITTERDESCBLOC'] = array('Sep' => 'line', 'Style' => 'block-default');

$Var['DESCRIPTION'] = array(
    'Type'    => 'char',
    'Input'   => 'xhtml',
    'MaxSize' => 600,
    'Format'  => ',',
    'Search'  => ['Type' => 'string'],
    'Height'  => 200,
    'Extra'   => 0x5312,
    'Path'    => 'public',
    'View'    => ViewLevels::DATA_EXPORT,
    'NbCols'  => 'all',
);

$Var['NEWSLINK'] = array(
    'Type'    => 'char',
    'Input'   => 'url',
    'Size'    => 40,
    'MaxSize' => 240,
    'Format'  => ',url',
    'Search'  => ['Type' => 'string'],
    'View'    => ViewLevels::DATA_EXPORT,
);

//  DISPLAY block
$Var['TWITTERDISPLAYBLOC'] = ['Sep' => 'line', 'Style' => 'block-default'];
$Var['POSITION'] = array(
    'Type'     => 'char',
    'Input'    => 'radio',
    'MaxSize'  => 240,
    'Values'   => 'str',
    'Format'   => ',',
    'View'     => ViewLevels::PRINT_EXPORT,
    'NbCols'   => 1,
    'Blank'    => false,
    'ViewOn'   => 'PUBLISHING=*',
    'Required' => 1,
);

//  ACTION block
$Var['ACTIONBLOC'] = ['Sep' => 'line', 'Style' => 'block-default',];
$Var['ACTION'] = ['Load' => 'SLIST'];

if (!empty($GLOBALS['Module']) and !empty($GLOBALS['Module']['supportal'])) {
    $Var['ACTIONBLOC']['ViewOn'] .= '+USERTYPE!network';
}
