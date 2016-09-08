<?php
/**
 * Twister service default config file
 */
return [
    /** The user to target */
    'user'    => env('TWISTER_USER', 'Determine'),
    /** API keys, sourced from the environment */
    'secrets' => [
        //  An "application" key and secret
        'consumer_key'        => env('TWISTER_CONSUMER_KEY'),
        'consumer_secret'     => env('TWISTER_CONSUMER_SECRET'),
        //  An "access token" key and secret
        'access_token'        => env('TWISTER_ACCESS_TOKEN'),
        'access_token_secret' => env('TWISTER_ACCESS_TOKEN_SECRET'),
    ],
];
