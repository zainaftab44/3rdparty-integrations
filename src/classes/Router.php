<?php

namespace src\classes;

use Exception;

/**
 * @author Zain Aftab
 * @copyright Zain Aftab - 2021
 *
 * Auto route handling
 */
class Router
{

    /**
     * Auto route handling. Created routes will be of the format /controllername/actionname. /controllername type of routes will be taken as /controllername/index
     *
     * @param mixed $path route path
     *
     * @return void
     */
    public function access($path)
    {
        $logger = Logger::getInstance();

        //default response in case route not found or nothing returing from route
        $data  = array('status' => DEFAULT_STATUS, 'message' => DEFAULT_MESSAGE);
        $resp  = null;

        //transforming route to repective action
        $path  = explode('/', $path);
        $class = '\\src\\controllers\\' . ucfirst($path[1]) . "Controller";
        $func  = (empty($path[2]) ? "index" : explode('?',$path[2])[0]) . "Action";

        try {
            $resp = call_user_func($class . "::" . $func);
        } catch (Exception $ex) {
            $data["exception"] = array('status' => 'failed', 'message' => "404 Not Found");
            $logger->info($ex->getMessage(), $ex);
        }

        $data = empty($resp) ? $data : $resp; // if no response found then default response is sent
        require __DIR__ . '/../views/index.php'; //prints the response in json format 

    }
}
