<?php

namespace src\controllers;

use src\classes\Curl;
use src\classes\Database;
use src\classes\Logger;
use src\classes\OauthHelper;

class TumblrController
{

    protected static $_baseurl         = 'https://api.tumblr.com';
    protected static $_consumer_key    = "<your-consumer-key>";
    protected static $_consumer_secret = '<your-consumer-secret>';

    public static function indexAction()
    {
        $logger = Logger::getInstance();
        $url    = 'https://www.tumblr.com/oauth/request_token';

        $headers = OauthHelper::getHeadersForOauth("POST", $url, self::$_consumer_key, self::$_consumer_secret);
        $c       = new Curl($url);

        $data = $c->performPost($headers);
        $logger->info($data);

        unset($data["oauth_callback_confirmed"]);
        Database::getInstance()->insert('tumblr_tokens', $data);

        return $data;
    }

    public static function getbloginfoAction()
    {
        // $logger = Logger::getInstance();

        $url = 'https://api.tumblr.com/v2/blog/' . $_GET['blog'] . '/info?api_key=' . self::$_consumer_key;
        $c   = new Curl($url);

        return $c->performGet();
    }
}
