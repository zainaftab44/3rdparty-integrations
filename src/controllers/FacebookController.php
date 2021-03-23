<?php

namespace src\controllers;
use src\classes\Logger;
use src\classes\Database;


class FacebookController
{

    protected static $api_version = 'v10.0';
    protected static $app_id      = '872251280199964';
    protected static $base_url    = 'https://graph.facebook.com/';


    public static function saveaccesstokenAction(){
        Logger::getInstance()->info($_POST);
        $db = Database::getInstance();
        $db->insert('access_tokens',array('site'=>'facebook','access_token'=>$_POST['accessToken'],'expiry'=>$_POST['expiresIn']));
        $db->insert('social_accounts',array('site'=>'facebook','user_id'=>$_POST['userID'],'full_response'=>json_encode($_POST)));
        return array('success'=>'success');
    }
}
