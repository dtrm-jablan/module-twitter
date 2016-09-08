<?php
/**
 * Twitter Integration Module
 *
 * @var array $appconf
 */
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Thujohn\Twitter\Twitter;

/**
 * @param string     $widgetName
 * @param array|null $context
 *
 * @return string
 */
function __twitter_widget_feed($widgetName, $context = null)
{
    /** @var \Thujohn\Twitter\Twitter $_service */
    if (null === ($_service = array_get($GLOBALS['appconf'], 'twitter.service'))) {
        require_once __DIR__ . '/../bootstrap/app.php';
        $_service = $_service = array_get($GLOBALS['appconf'], 'twitter.service');
    }

    $_html = null;

    try {
        $_response = $_service->getUserTimeline(['count' => 20, 'format' => 'array']);

        foreach ($_response as $_tweet) {
            $_html[] = view('tweet', $_tweet)->render();
        }
    } catch (Exception $e) {
        return Log::error($e, $_service->logs());
    }

    return $_html ? implode(PHP_EOL, $_html) : null;
}

