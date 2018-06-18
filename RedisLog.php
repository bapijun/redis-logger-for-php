<?php
namespace RedisLog;
use \Psr\Log\AbstractLogger;
require 'vendor/autoload.php';
/** 
 * A log class write log into a redis list 
 * used The phpredis extension 
 * @author bapijun <bapijun@foxmail.com>
 * @link https://github.com/phpredis/phpredis#introspection 
 */
class RedisLog extends \Psr\Log\AbstractLogger
{
   /**
     * Detailed debug information
     */
    const DEBUG = 100;

    /**
     * Interesting events
     *
     * Examples: User logs in, SQL logs.
     */
    const INFO = 200;

    /**
     * Uncommon events
     */
    const NOTICE = 250;

    /**
     * Exceptional occurrences that are not errors
     *
     * Examples: Use of deprecated APIs, poor use of an API,
     * undesirable things that are not necessarily wrong.
     */
    const WARNING = 300;

    /**
     * Runtime errors
     */
    const ERROR = 400;

    /**
     * Critical conditions
     *
     * Example: Application component unavailable, unexpected exception.
     */
    const CRITICAL = 500;

    /**
     * Action must be taken immediately
     *
     * Example: Entire website down, database unavailable, etc.
     * This should trigger the SMS alerts and wake you up.
     */
    const ALERT = 550;

    /**
     * Urgent alert.
     */
    const EMERGENCY = 600;

    /**
     * 
     * @var array the level name
     */
    protected static $levels = array(
        100 => 'DEBUG',
        200 => 'INFO',
        250 => 'NOTICE',
        300 => 'WARNING',
        400 => 'ERROR',
        500 => 'CRITICAL',
        550 => 'ALERT',
        600 => 'EMERGENCY',
    );

   
    /**
     * 
     * @var redisClient
     */
    private $redis;
    /**
     * @var a redis list for  log storage 
     */
    private $logList;
    /**
     * 构造函数
     */
    
    public function __construct($redis, $logList = 'redis_log')
    {
        $this->redis = $redis;
       
        $this->logList = $logList;
    }

    /**
     */
    function __destruct()
    {
        

    }
    /**
     * add a log into redis list 
     * @param int $level the logging level
     * @param string $message the log message
     * @param array $context the log context
     * @return unknown redis case of Failure 
     */
    public function addRecord($level, $message, $context=[]) 
    {
        $time = date('Y-m-d H:i:s');
       
        $listMessage =  '[' . $time . ']' . '  ' . $levels[$level] . '  ' .  $message  . '  ' . json_encode($context);
        
        return  $this->redis->lPush($this->logList, $listMessage);
         
    }
    /**
     * add the log at the INFO level
     * @param string $message the log message
     * @param array $context the log context
     * @return unknown redis case of Failure
     */
    public function addInfo($message, $context  = [])
    {
        return $this->addRecord(self::INFO, $message, $context);
    }
    /**
     * add the log at the ERROR level
     * @param string $message the log message
     * @param array $context the log context
     * @return unknown redis case of Failure
     */
    public function addError($message, $context  = [])
    {
        return $this->addRecord(self::ERROR, $message, $context);
    }
    /**
     * add the log at the DEBUG level
     * @param string $message the log message
     * @param array $context the log context
     * @return unknown redis case of Failure
     */
    public function addDebug($message, $context  = [])
    {
        return $this->addRecord(self::DEBUG, $message, $context);
    }
    /**
     * add the log at the NOTICE level
     * @param string $message the log message
     * @param array $context the log context
     * @return unknown redis case of Failure
     * @return unknown redis case of Failure
     */
    public function addNotice($message, $context  = [])
    {
        return $this->addRecord(self::NOTICE, $message, $context);
    }
    /**
     * add the log at the WARNING level
     * @param string $message the log message
     * @param array $context the log context
     * @return unknown redis case of Failure
     * @return unknown redis case of Failure
     */
    public function addWarning($message, $context  = [])
    {
        return $this->addRecord(self::WARNING, $message, $context);
    }
    /**
     * add the log at the EMERGENCY level
     * @param string $message the log message
     * @param array $context the log context
     * @return unknown redis case of Failure
     * @return unknown redis case of Failure
     */
    public function addEmergency($message, $context  = [])
    {
        return $this->addRecord(self::EMERGENCY, $message, $context);
    }
    /**
     * add the log at the ALERT level
     * @param string $message the log message
     * @param array $context the log context
     * @return unknown redis case of Failure
     * @return unknown redis case of Failure
     */
    public function addAlert($message, $context  = [])
    {
        return $this->addRecord(self::ALERT, $message, $context);
    }
    /**
     * add the log at the CRITICAL level
     * @param string $message the log message
     * @param array $context the log context
     * @return unknown redis case of Failure
     * @return unknown redis case of Failure
     */
    public function addCritical($message, $context  = [])
    {
        return $this->addRecord(self::CRITICAL, $message, $context);
    }
    /**
     * Gets the name of the logging level.
     *
     * @param  integer $level
     * @return string level name
     */
    public static function getLevelName($level)
    {
        return self::$levels[$level];
    }
    
     /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        $this->addRecord($level, $message, $context);
    }
}

