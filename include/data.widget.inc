<?php
/**
 * Twitter Integration Module
 */
use ChaoticWave\Twister\Facades\Twister;
use Illuminate\Support\Facades\Log;

///**
// * @param string     $widgetName
// * @param array|null $context
// *
// * @return string
// */
//function __twitter_widget_feed($widgetName, $context = null)
//{
//    $_html = null;
//
//    try {
//        $_response = Twister::getUserTimeline(3);
//
//        foreach ($_response as $_tweet) {
//            $_tweet->direct_link = 'https://twitter.com/Determine/status/' . $_tweet->id_str;
//            $_html[] = view('tweet', (array)$_tweet)->render();
//        }
//    } catch (Exception $e) {
//        /** @noinspection PhpUndefinedMethodInspection */
//        Log::error($e);
//    }
//
//    return $_html ? implode(PHP_EOL, $_html) : null;
//}
//
