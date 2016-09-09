<?php
///**
// * Twitter Integration Module
// */
//
///**
// * @param string     $widgetName
// * @param array|null $context
// *
// * @return string
// */
//function __twitter_widget_feed($widgetName = null, $context = null)
//{
//    $_html = null;
//
//    try {
//        $_response = \ChaoticWave\Twister\Facades\Twister::getUserTimeline();
//
//        foreach ($_response as $_tweet) {
//            $_html[] = view('tweet', (array)$_tweet)->render();
//        }
//    } catch (Exception $_ex) {
//        /** @var \Monolog\Logger $Log */
//        global $Log;
//
//        if ($Log) {
//            $Log->error('[twitter-widget] exception: ' . $_ex->getMessage());
//        }
//    }
//
//    return $_html ? implode(PHP_EOL, $_html) : null;
//}
//
////******************************************************************************
////* Functions
////******************************************************************************
//
//if (!function_exists('__bootstrap_twitter_module')) {
//    /**
//     * Bootstrap the module
//     *
//     * @var \Determine\Module\Twitter\Module $__twitter
//     */
//    function __bootstrap_twitter_module()
//    {
//        global $appconf;
//
//        if (isset($appconf['twitter']['service'])) {
//            return;
//        }
//
//        $__twitter = require_once(__DIR__ . '/../bootstrap/app.php');
//
//        //  Don't do this for CLI commands
//        if (!is_bool($__twitter) && 'cli' !== PHP_SAPI) {
//            if (!empty($_config = config('twitter'))) {
//                $_config['service'] = $__twitter;
//                $appconf['twitter'] = $_config;
//            }
//
//            $__twitter->refreshGlobalConfig();
//        }
//
//        //  Turn down errors
//        error_reporting(E_ERROR);
//    }
//}
//
////******************************************************************************
////* Logic
////******************************************************************************
//
////  Module bootstrap
//__bootstrap_twitter_module();
