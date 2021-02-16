<?php
/**
 * @author Zain Aftab
 * @copyright Zain Aftab - 2021
 */

require_once 'config/config.php';
$mapping = array();

$controllers = array_diff(scandir(__DIR__ . '/src/controllers'), array('.', '..'));
$classes = array_diff(scandir(__DIR__ . '/src/classes'), array('.', '..'));

foreach ($classes as $class) {
    $mapping['src\\classes\\' . (str_split($class, strpos($class, '.php'))[0])] = __DIR__ . '/src/classes/' . $class;
}
foreach ($controllers as $controller) {
    $mapping['src\\controllers\\' . (str_split($controller, strpos($controller, '.php'))[0])] = __DIR__ . '/src/controllers/' . $controller;
}

spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require $mapping[$class];
    }
}, true);
