<?php
/**
 * Twitter Integration Module
 */
//******************************************************************************
//* Callbacks
//******************************************************************************

/**
 * @param string     $widgetName
 * @param array|null $context
 *
 * @return string
 */
function __twitter_widget_feed($widgetName, $context = null)
{
    $_html = null;

    try {
        $_response = \ChaoticWave\Twister\Facades\Twister::getUserTimeline(3);

        foreach ($_response as $_tweet) {
            $_tweet->direct_link = 'https://twitter.com/' . config('twister.user') . '/status/' . $_tweet->id_str;
            $_html[] = view('tweet', (array)$_tweet)->render();
        }
    } catch (Exception $e) {
        /** @noinspection PhpUndefinedClassInspection */
        AppLog::error($e);
    }

    return $_html ? implode(' ', $_html) : null;
}

//******************************************************************************
//* Functions
//******************************************************************************

if (!function_exists('__bootstrap_twitter_widget')) {
    /**
     * Bootstrap the module
     *
     * @var \Determine\Module\Twitter\Module $__twitter
     *
     * @return \Determine\Module\Twitter\Module
     */
    function __bootstrap_twitter_widget()
    {
        global $appconf;

        if (isset($appconf['twitter']['service'])) {
            return $appconf['twitter']['service'];
        }

        $__twitter = require_once(__DIR__ . '/../bootstrap/app.php');

        //  Don't do this for CLI commands
        if (!is_bool($__twitter) && 'cli' !== PHP_SAPI) {
            if (!empty($_config = config('twitter'))) {
                $_config['service'] = $__twitter;
                $appconf['twitter'] = $_config;
            }

            $__twitter->refreshGlobalConfig();
        }

        //  Turn down errors
        error_reporting(E_ERROR);

        return $__twitter;
    }
}

//******************************************************************************
//* Logic
//******************************************************************************

//  Module bootstrap
__bootstrap_twitter_widget();
