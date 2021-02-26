<?php

namespace src\controllers;

use src\classes\Curl;
use src\classes\Logger;

class QuotesController
{

    public static function funnyAction()
    {
        $logger = Logger::getInstance();

        $url = (rand() % 2 == 1) ? 'https://www.bojo.ai/random/quote' : 'https://tronalddump.io/random/quote';

        $logger->debug($url);

        $c      = new Curl($url);
        $output = $c->performGet();

        if (isset($output["_embedded"]['author'])) {
            $output["_embedded"]['authors'] = $output["_embedded"]['author'];
            unset($output["_embedded"]['author']);
        }

        // $logger->debug($output['value'], $output["_embedded"]['authors'][0]['name']);
        return array("quote" => $output['value'], "author" => $output["_embedded"]['authors'][0]['name']);
    }

}
