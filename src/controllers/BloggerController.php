<?php

namespace src\controllers;

use src\classes\Curl;
use src\classes\Database;
use src\classes\Logger;

class BloggerController
{
    protected static $client_id     = '707703118466-gvcj2sjjic0r1ackbu2jaf5ss842eld9.apps.googleusercontent.com';
    protected static $client_secret = 'YXx-PcIbuoEkdTs7rFOQHKsr';
    protected static $api_key       = 'AIzaSyBWaiGYnSn3LBrVksBTRt8WYOJ3oV-vxUc';
    protected static $base_url      = 'https://www.googleapis.com/blogger/v3/';

    public static function saveaccesstokenAction()
    {
        $token = array('site' => 'blogger', 'access_token' => $_POST['access_token'], 'expiry' => $_POST['expires_in'], 'added' => date('Y-m-d H:i:s'));

        Logger::getInstance()->info($token);
        Database::getInstance()->insert('access_tokens', $token);

        return array("status" => "success");
    }

    public static function myprofAction()
    {
        $url   = self::$base_url . "users/self";
        $token = Database::getInstance()->select('access_tokens', array('access_token'), array('site' => 'blogger'), array('added' => 'desc'), 1)[0]['access_token'];
        Logger::getInstance()->info($token);

        $header = array("Authorization: Bearer " . $token);
        Logger::getInstance()->info($header);

        $ch = new Curl($url);
        var_dump($ch->performGet($header));

    }

    public static function createpostAction()
    {
        $_POST['blog_id'] = '8076964189145260729';
        $_POST['title']   = (empty($_POST['title'])) ? "Post without title auto poster " . rand() : $_POST['title'];
        $_POST['content'] = (empty($_POST['content'])) ? "With <b>exciting</b> content..." : $_POST['content'];
        $url              = self::$base_url . "blogs/$_POST[blog_id]/posts/";
        $token            = Database::getInstance()->select('access_tokens', array('access_token'), array('site' => 'blogger'), array('added' => 'desc'), 1)[0]['access_token'];
        // Logger::getInstance()->info($token);

        $data = array(
            "kind"    => "blogger#post",
            "blog"    => array(
                "id" => "$_POST[blog_id]",
            ),
            "title"   => $_POST['title'],
            "content" => $_POST['content'],
        );

        $header = array("Authorization: Bearer " . $token
            , "Content-Type: application/json");
        Logger::getInstance()->info($header);

        $ch = new Curl($url);
        return $ch->performPost($header, $data);

    }

    public static function getblogAction()
    {
        $url = self::$base_url . 'blogs/' . (!empty($_GET['url']) ? 'byurl?url=' . $_GET['url'] : $_GET['id']);

        $query = parse_url($url, PHP_URL_QUERY);
        $url .= (($query) ? '&' : '?') . "key=" . self::$api_key;

        $ch = new Curl($url);
        return $ch->performGet();
    }

    public static function getpostsAction()
    {
        $url = self::$base_url . "blogs/$_GET[id]/posts?key=" . self::$api_key;

        $ch = new Curl($url);
        return $ch->performGet();
    }

    public static function searchpostsAction()
    {
        $url = self::$base_url . "blogs/$_GET[id]/posts/search?q=$_GET[q]&key=" . self::$api_key;

        $ch = new Curl($url);
        return $ch->performGet();
    }
}
