<?php
/**
 * Twitter Integration Module
 */
use ChaoticWave\Twister\Facades\Twister;
use Illuminate\Support\Facades\Log;

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
        $_response = Twister::getUserTimeline();

        foreach ($_response as $_tweet) {
            $_html[] = view('tweet', (array)$_tweet)->render();
        }
    } catch (Exception $e) {
        /** @noinspection PhpUndefinedMethodInspection */
        Log::error($e);
    }

    return $_html ? implode(PHP_EOL, $_html) : null;
}

