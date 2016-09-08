<?php namespace Determine\Module\Twitter;

use ChaoticWave\BlueVelvet\Enums\BaseEnum;
use ChaoticWave\BlueVelvet\Utility\Uri;

class LinkTypes extends BaseEnum
{
    //******************************************************************************
    //* Constants
    //******************************************************************************

    /**
     * @var int Link to tweet
     */
    const TWEET = 0;
    /**
     * @var int Reply link
     */
    const REPLY = 1;
    /**
     * @var int Retweet link
     */
    const RETWEET = 2;
    /**
     * @var int Link to user
     */
    const USER = 3;
    /**
     * @var int Add Favorite link
     */
    const ADD_FAVORITE = 4;
    /**
     * @var string The base Twitter url
     */
    const BASE_URL = 'https://twitter.com';

    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var array The URI associated with each type
     */
    protected static $_typeUris = [
        self::TWEET        => '/{screen_name}/status/{tweet_id}',
        self::REPLY        => '/intent/tweet?in_reply_to={tweet_id}',
        self::RETWEET      => '/intent/retweet?tweet_id={tweet_id}',
        self::USER         => '/{screen_name}',
        self::ADD_FAVORITE => '/intent/favorite?tweet_id={tweet_id}',
    ];

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * @param int         $type
     * @param string|null $screenName
     * @param string|null $tweetId
     *
     * @return mixed
     */
    public static function getLinkUrl($type, $screenName = null, $tweetId = null)
    {
        if (null === ($_uri = array_get(static::$_typeUris, $type))) {
            throw new \InvalidArgumentException('The link type "' . $type . '" is not valid.');
        }

        return str_ireplace(['{screen_name}', '{tweet_id}'], [$screenName, $tweetId], Uri::segment([static::BASE_URL, $_uri], false));
    }
}
