<?php
/**
 * Twitter Integration Module
 *
 * @var array  $Module
 * @var string $mod
 * @var array  $appconf
 * @var array  $OverLoad
 */

//******************************************************************************
//* Functions
//******************************************************************************

use Determine\Module\Twitter\Module;

if (!function_exists('__bootstrap_twitter_module')) {
    /**
     * Bootstrap the module
     *
     * @var Module $__twitter
     */
    function __bootstrap_twitter_module()
    {
        if (isset($appconf['twitter']['service'])) {
            return;
        }

        $__twitter = require_once(__DIR__ . '/bootstrap/app.php');

        //  Don't do this for CLI commands
        if (!is_bool($__twitter) && 'cli' !== PHP_SAPI) {
            if (is_array($_config = config('twitter'))) {
                $_config['service'] = $__twitter;
                $appconf['twitter'] = $_config;
            }

            $__twitter->refreshGlobalConfig();
        }

        //  Turn down errors
        error_reporting(E_ERROR);
    }
}

//******************************************************************************
//* Logic
//******************************************************************************

//  Module bootstrap
__bootstrap_twitter_module();
