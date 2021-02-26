<?php
/**
 * @author Zain Aftab
 * @copyright Zain Aftab - 2021
 */
include 'autoloader.php';

use src\classes\Router;

// router.php
$route = new Router();
die($route->access($_SERVER["REQUEST_URI"]));
