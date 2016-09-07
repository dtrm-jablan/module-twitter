<?php
/**
 * Twitter integration module configuration
 */
use Monolog\Logger;

return [
    /** Where my logs go, relative to "files/" ($appconf['root_file']) */
    'log-path'            => '/logs/twitter.log',
    'log-level'           => Logger::DEBUG,
    'debug'               => false,
    'API_URL'             => 'api.twitter.com',
    'UPLOAD_URL'          => 'upload.twitter.com',
    'API_VERSION'         => '1.1',
    'AUTHENTICATE_URL'    => 'https://api.twitter.com/oauth/authenticate',
    'AUTHORIZE_URL'       => 'https://api.twitter.com/oauth/authorize',
    'ACCESS_TOKEN_URL'    => 'https://api.twitter.com/oauth/access_token',
    'REQUEST_TOKEN_URL'   => 'https://api.twitter.com/oauth/request_token',
    'USE_SSL'             => true,
    'CONSUMER_KEY'        => function_exists('env') ? env('TWITTER_CONSUMER_KEY') : '4CTQIkq8bDrbeOWvyDqjI6GCn',
    'CONSUMER_SECRET'     => function_exists('env') ? env('TWITTER_CONSUMER_SECRET') : 'bWPp6iInLkVZHcFUUzo4kayWlux79DTzcJCkEY2jjwa9abQqTb',
    'ACCESS_TOKEN'        => function_exists('env') ? env('TWITTER_ACCESS_TOKEN') : '14147388-DZLMfmVKDRQqfjy9m2msGt4UvYAyWo7BkkFRqaOg6',
    'ACCESS_TOKEN_SECRET' => function_exists('env') ? env('TWITTER_ACCESS_TOKEN_SECRET') : 'LKX7JirakWz6MspN5rXrNOp3tmJJb4IAdJ6JKDB8TGrqf',
];
