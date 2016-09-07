<?php
/**
 * Twitter Integration Module
 *
 * @var array  $Module
 * @var string $AppMenu
 */

$_menu = array_get($Module, 'twitter.tab', empty($AppMenu['setorga']) ? 'maintenance' : 'setorga');

$AppMenu[$_menu]['collaborative'] = ['sep' => 1];
$AppMenu[$_menu]['TWITTERID'] = ['rule' => 'settings', 'group' => 'list'];
