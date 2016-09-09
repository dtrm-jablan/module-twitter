<?php
/**
 * Twitter Integration Module
 *
 * @var array $DbWidget
 */
$DbWidget['twitter_primary'] = ['func' => '__twitter_widget_feed'];

//******************************************************************************
//* Functions
//******************************************************************************

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
            if (is_array($_config = config('ttwitter'))) {
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
