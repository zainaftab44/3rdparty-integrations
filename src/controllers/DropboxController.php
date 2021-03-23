<?php

namespace src\controllers;

use src\classes\Curl;
use src\classes\Database;
use src\classes\Logger;

class DropboxController
{

    protected static $base_url   = 'https://api.dropboxapi.com/';
    protected static $app_key    = 'sxwnahyx2wgui4d';
    protected static $app_secret = 'mr6r05hua7wvfpi';

    protected static $access_token = 'sl.AsMxxvX4LCJRui7-TdJ3b-8d8R10KRJOgvzfUXaueN0zuT8dZ0NPXb-zep5Vx-9PybqQ535yBIcFN4W7-pwR1LUTWZloeqV1oiTQVNlRAZCwHyeLPbgaClKZC5YcA95607G_n4U';

    public static function saveaccesstokenAction()
    {

        $token  = array('site' => 'dropbox', 'access_token' => $_POST['access_token'], 'expiry' => -1, 'added' => date('Y-m-d H:i:s'));
        $social = array('site' => 'dropbox', 'userid' => $_POST['uid'], "accountid" => $_POST['account_id'], 'added' => date('Y-m-d H:i:s'));

        Logger::getInstance()->info($_POST);
        Database::getInstance()->insert('access_tokens', $token);
        Database::getInstance()->insert('social_accounts', $social);

        return array("status" => "success");
    }

    public static function getprofileAction()
    {
        $url        = self::$base_url . "2/users/get_account";
        $token      = Database::getInstance()->select('access_tokens', array('access_token'), array('site' => 'dropbox'), array('added' => 'desc'), 1)[0]['access_token'];
        $account_id = Database::getInstance()->select('social_accounts', array('accountid'), array('site' => 'dropbox'), array('added' => 'desc'), 1)[0]['accountid'];
        // Logger::getInstance()->info($token);
        $data = array('account_id' => $account_id);

        $header = array("Authorization: Bearer " . $token
            , "Content-Type: application/json"
            , 'Content-Length: ' . strlen(json_encode($data)));

        $ch   = new Curl($url);
        $resp = ($ch->performPost($header, $data));

        $resp['name'] = $resp['name']['display_name'];
        Logger::getInstance()->info($resp);

        return $resp;
    }

    public static function getfilesAction()
    {
        $url   = self::$base_url . "2/files/list_folder";
        $token = Database::getInstance()->select('access_tokens', array('access_token'), array('site' => 'dropbox'), array('added' => 'desc'), 1)[0]['access_token'];
        $data  = array(
            'path'      => '',
            'recursive' => true,
        );
        $header = array(
            "Authorization: Bearer " . $token
            , "Content-Type: application/json"
            , 'Content-Length: ' . strlen(json_encode($data)),
        );

        $ch   = new Curl($url);
        $resp = ($ch->performPost($header, $data));
        for ($i = 0; $i < sizeof($resp['entries']); $i++) {
            if (strcmp($resp['entries'][$i]['.tag'], 'folder') == 0) {
                unset($resp['entries'][$i]);
            }
        }


        return $resp;
    }
}
