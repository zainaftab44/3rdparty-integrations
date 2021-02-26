<?php

namespace src\controllers;

use src\classes\Curl;

class MixmaxController
{
    protected static $access_token = '<your-access-token>';
    protected static $base_url     = "https://api.mixmax.com/v1";

    public static function indexAction()
    {
        $ch = new Curl(self::$base_url . '/users/me');
        return $ch->performGet(array("X-API-Token:" . self::$access_token));
    }

    public static function contactsAction()
    {
        $ch = new Curl(self::$base_url . '/contacts');
        return $ch->performGet(array("X-API-Token:" . self::$access_token));
    }

    public static function snippetsAction()
    {
        $ch = new Curl(self::$base_url . '/codesnippets');
        return $ch->performGet(array("X-API-Token:" . self::$access_token));
    }
}
