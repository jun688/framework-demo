<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 16:15
 */

namespace Core;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

class Log
{
    private static $loggers;

    /**
     * 日志路径
     * @var string
     */
    private static $filePath = APP_PATH . 'Logs/';

    /**
     * 留存时间
     * @var int
     */
    private static $maxFiles = 31;


    /**
     * 日志等级
     * @var int
     */
    private static $level = Logger::DEBUG;


    /**
     * 文件读写权限
     * @var int
     */
    private static $fileChmod = 0666;


    /**
     * monolog日志
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        $logger = self::createLogger($name);

        $message         = empty($arguments[0]) ? '' : $arguments[0];
        $context         = empty($arguments[1]) ? [] : $arguments[1];
        $levelName       = empty($arguments[2]) ? $name : $arguments[2];
        $backtraceOffset = empty($arguments[3]) ? 0 : $arguments[3];

        $level = Logger::toMonologLevel($levelName);
        if (!is_int($level)) {
            $level = Logger::INFO;
        }

        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $idx = 0 + $backtraceOffset;

        $trace = basename($backtrace[$idx]['file']) . ':' . $backtrace[$idx]['line'];
        if (!empty($backtrace[$idx + 1]['function'])) {
            $trace .= '##';
            $trace .= $backtrace[$idx + 1]['function'];
        }

        $message = sprintf('==>LOG: %s -- %s', $message, $trace);

        return $logger->addRecord($level, $message, $context);
    }

    /**
     * 创建日志
     * @param $name
     * @return mixed
     */
    public static function createLogger($name)
    {
        if (empty(self::$loggers[$name])) {

            $category  = $_SERVER['SERVER_NAME'];
            //日志文件目录
            $filePath  = self::$filePath;
            //日志保存时间
            $maxFiles  = self::$maxFiles;
            //日志等级
            $level     = self::$level;
            //日志权限
            $fileChmod = self::$fileChmod;

            //创建日志
            $logger = new Logger($category);
            //日志文件操作
            $handle = new RotatingFileHandler("{$filePath}{$name}.log", $maxFiles, $level, true, $fileChmod);
            //日志格式
            $formatter = new LineFormatter("%datetime% %channel% : %level_name% %message% %context% %extra%\n",
                "Y-m-d H:i:s", false, true);

            $handle->setFormatter($formatter);
            $logger->pushHandler($handle);

            self::$loggers[$name] = $logger;
        }

        return self::$loggers[$name];
    }

}