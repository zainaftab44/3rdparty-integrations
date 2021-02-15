<?php
namespace src\classes;

use DateTime;
use DateTimeZone;
use function PHPSTORM_META\type;

/**
 * @author Zain Aftab
 * @copyright Zain Aftab - 2021
 *  
 * Logger
 * Basic class to log messasges/data at different levels
 */
class Logger
{

    // Hold the class instance.
    private static $instance = null;

    const ERROR  = "ERROR";
    const WARN   = "WARNING";
    const NOTICE = "NOTICE";
    const ALERT  = "ALERT";
    const INFO   = "INFO";
    const DEBUG  = "DEBUG";

    protected $_file;

    protected $timestamp = 'D M d Y H:i:s';

    private function __construct()
    {
        $this->_file = fopen(LOG_LOCATION, 'a'); //both (a)ppending, and (w)riting will work
    }

    protected function __clone()
    {}

    public static function getInstance()
    {
        self::$instance = empty(self::$instance) ? new static() : self::$instance;
        return self::$instance;
    }

    protected function write($level, $data)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        // $prepend = "[" . date($this->timestamp) . "][" . $level . "][" . $trace[2]['class'] . ":" . $trace[1]['line'] . "][" . $trace[2]['function'] . "]";
        $now     = new DateTime("now", new DateTimeZone(TIMEZONE));
        $prepend = "[" . $now->format($this->timestamp) . "][$_SERVER[REMOTE_ADDR]:$_SERVER[REMOTE_PORT]][$_SERVER[REQUEST_URI]][" . $level . " ][" . $trace[2]['class'] . "::" . $trace[2]['function'] . "(" . $trace[1]['line'] . ")]";

        if (!empty($data)) {
            fwrite($this->_file, $prepend . " $data[0]\n");
        }

        if (sizeof($data) > 1) {
            for ($i = 1; $i < sizeof($data); $i++) {
                fwrite($this->_file, $prepend . " Printing received " . (gettype($data[$i]) == 'object' ? get_class($data[$i]) . " Object" : gettype($data[$i])) . "\n" . print_r($data[$i], true) . "\n");
            }
        }

    }

    /**
     * Print error logs
     * @param string message message to be printed on log
     * @param mixed data,... OPTIONAL any number params of any type to be printed on logs
     *
     * @return void
     */
    public function error($message, $data)
    {
        $this->write(self::ERROR, func_get_args());
    }

    /**
     * Print warning logs
     * @param string message message to be printed on log
     * @param mixed data,... OPTIONAL any number params of any type to be printed on logs
     *
     * @return void
     */
    public function warn($message, $data)
    {
        $this->write(self::WARN, func_get_args());
    }

    /**
     * Print notice logs
     * @param string message message to be printed on log
     * @param mixed data,... OPTIONAL any number params of any type to be printed on logs
     *
     * @return void
     */
    public function notice($message, $data)
    {
        $this->write(self::NOTICE, func_get_args());
    }

    /**
     * Print alert logs
     * @param string message message to be printed on log
     * @param mixed data,... OPTIONAL any number params of any type to be printed on logs
     *
     * @return void
     */
    public function alert($message, $data)
    {
        $this->write(self::ALERT, func_get_args());
    }

    /**
     * Print info logs
     * @param string message message to be printed on log
     * @param mixed data,... OPTIONAL any number params of any type to be printed on logs
     *
     * @return void
     */
    public function info($message, $data)
    {
        $this->write(self::INFO, func_get_args());
    }

    /**
     * Print debug logs
     * @param string message message to be printed on log
     * @param mixed data,... OPTIONAL any number params of any type to be printed on logs
     *
     * @return void
     */
    public function debug($message, $data)
    {
        $this->write(self::DEBUG, func_get_args());
    }

}
