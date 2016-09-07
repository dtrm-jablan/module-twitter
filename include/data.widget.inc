<?php
/**
 * Twitter Integration Module
 */

use Thujohn\Twitter\Facades\Twitter;

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
        $_response = Twitter::getUserTimeline(['count' => 20, 'format' => 'array']);

        foreach ($_response as $_tweet) {
            $_html[] = view('tweet', $_tweet)->render();
        }
    } catch (Exception $e) {
        Log::error($e, Twitter::logs());
    }

    return $_html ? implode(PHP_EOL, $_html) : null;
}

