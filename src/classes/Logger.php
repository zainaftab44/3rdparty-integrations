<?php
namespace src\classes;

use DateTime;
use DateTimeZone;

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

    /**
     * Returns a created/new logger instance
     *
     * @return Logger
     */
    public static function getInstance()
    {
        self::$instance = empty(self::$instance) ? new static() : self::$instance;
        return self::$instance;
    }

    /**
     * Logs data to LOG_LOCATION
     *
     * @param string $level log level
     * @param mixed $data any number of strings/objects/arrays to log
     *
     * @return void
     */
    protected function write($level, $data)
    {
        $trace   = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $now     = new DateTime("now", new DateTimeZone(TIMEZONE));
        $prepend = "[" . $now->format($this->timestamp) . "][$_SERVER[REMOTE_ADDR]:$_SERVER[REMOTE_PORT]][$_SERVER[REQUEST_URI]][" . $level . " ][" . $trace[2]['class'] . "::" . $trace[2]['function'] . "(" . $trace[1]['line'] . ")]";

        for ($i = 0; $i < sizeof($data); $i++) {
            fwrite($this->_file, $prepend . " Printing received " . (gettype($data[$i]) == 'object' ? get_class($data[$i]) . " Object" : gettype($data[$i])) . "\n" . print_r($data[$i], true) . "\n");
        }

    }

    /**
     * Print error logs
     * @param string message message to be printed on log
     * @param mixed data,... OPTIONAL any number params of any type to be printed on logs
     *
     * @return void
     */
    public function error($message, $data = null)
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
    public function warn($message, $data = null)
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
    public function notice($message, $data = null)
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
    public function alert($message, $data = null)
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
    public function info($message, $data = null)
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
    public function debug($message, $data = null)
    {
        $this->write(self::DEBUG, func_get_args());
    }

}
