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
    'USE_SSL'             => true,
    'API_URL'             => 'api.twitter.com',
    'UPLOAD_URL'          => 'upload.twitter.com',
    'API_VERSION'         => '1.1',
    'AUTHENTICATE_URL'    => 'https://api.twitter.com/oauth/authenticate',
    'AUTHORIZE_URL'       => 'https://api.twitter.com/oauth/authorize',
    'ACCESS_TOKEN_URL'    => 'https://api.twitter.com/oauth/access_token',
    'REQUEST_TOKEN_URL'   => 'https://api.twitter.com/oauth/request_token',
    'CONSUMER_KEY'        => env('TWITTER_CONSUMER_KEY'),
    'CONSUMER_SECRET'     => env('TWITTER_CONSUMER_SECRET'),
    'ACCESS_TOKEN'        => env('TWITTER_ACCESS_TOKEN'),
    'ACCESS_TOKEN_SECRET' => env('TWITTER_ACCESS_TOKEN_SECRET'),
];
