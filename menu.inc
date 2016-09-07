<?php
/**
 * Twitter Integration Module
 *
 * @var array  $Module
 * @var string $AppMenu
 * @var array  $appconf
 * @var array  $OverLoad
 */
$_menu = (!empty($Module['twitter']['tab']) ? $Module['twitter']['tab'] : (!empty($AppMenu['setorga']) ? 'setorga' : 'maintenance'));

$AppMenu[$_menu]['collaborative'] = ['sep' => 1];
$AppMenu[$_menu]['TWITTERID'] = ['rule' => 'settings', 'group' => 'list'];
