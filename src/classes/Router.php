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

    protected $__status  = 404;
    protected $__message = "Unable to load resource";

    public function access($path)
    {
        $logger = Logger::getInstance();

        $data  = array('status' => $this->__status, 'message' => $this->__message);
        $resp  = null;
        $path  = explode('/', $path);
        $class = '\\src\\controllers\\' . ucfirst($path[1]) . "Controller";
        $func  = (empty($path[2]) ? "index" : $path[2])."Action";

        try {
            $resp = call_user_func($class . "::" . $func);
        } catch (Exception $ex) {
            $data["exception"] = array('status' => 'failed', 'message' => "404 Not Found");
            $logger->info($ex->getMessage(), $ex);
        }

        $data = empty($resp) ? $data : $resp;
        require __DIR__ . '/../views/index.php';

    }
}
