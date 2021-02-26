<?

namespace src\controllers;

use src\classes\Curl;
use src\classes\Logger;

class IpController
{

    protected static $_baseurl      = 'http://api.ipstack.com/';
    protected static $_access_token = '<your-access-token>';

    public static function getlocationAction()
    {

        $logger = Logger::getInstance();
        $ip     = $_SERVER['REMOTE_ADDR'];
        $url    = self::$_baseurl . "$ip?access_key=" . self::$_access_token . "&output=json";
        $logger->debug($url);

        $ch = new Curl($url);
        return $ch->performGet();
    }
}
