<?php

namespace eYaf;

define('DS', DIRECTORY_SEPARATOR);

class Logger extends \SplFileObject
{
    const RED = '1;31m';
    const GREEN = '1;32m';
    const PURPLE = '1;35m';
    const CYAN = '1;36m';
    const WHITE = '1;37m';

    const RESET_SEQ = "\033[0m";
    const COLOR_SEQ = "\033[";
    const BOLD_SEQ = "\033[1m";

    private static $start_time;

    private static $memory;

    private static $logger_instance;

    /**
     * 日志记录开始
     * 记录日志时间及内存状态
     */
    public static function startLogging()
    {
        self::$start_time = microtime(true);
        self::$memory = memory_get_usage(true);
        $buffer = self::COLOR_SEQ . self::GREEN
            . "Started at : [" . date('H:i:s d-m-Y', time()) . "]"
            . self::RESET_SEQ;
        static::getLogger()->log($buffer);
    }

    /**
     * 日志记录结束
     * 记录日志时间及内存状态
     */
    public static function stopLogging()
    {
        $buffer = self::COLOR_SEQ . self::GREEN . "Completed in "
            . number_format((microtime(true) - self::$start_time) * 1000, 0)
            . "ms | "
            . "Mem Usage: ("
            . number_format((memory_get_usage(true) - self::$memory) / (1024), 0, ",", ".")
            . " kb)"
            . self::RESET_SEQ;
        static::getLogger()->log($buffer);
    }

    /**
     * 获取Log 实例
     * 初始化LOG的文件路径
     * @param null $env
     * @param string $open_mode  读取方式 fopen() 中 mode
     * @return mixed
     */
    public static function getLogger($env = null, $open_mode = "a")
    {
        if (static::$logger_instance) return static::$logger_instance;
        $env = $env ? : \Yaf\ENVIRON;
        $filename = APP_PATH . '/log' . DS . $env . '.log';
        static::$logger_instance = new static($filename, $open_mode);
        return static::$logger_instance;
    }

    /**
     * 构造函数
     * 初始化日志文件地址,提交父类构造函数
     * @param null $filename
     * @param string $open_mode
     */
    public function __construct($filename = null, $open_mode = "a")
    {
        $filename = $filename ? : APP_PATH . "/log" . DS . \Yaf\ENVIRON . ".log";
        parent::__construct($filename, $open_mode);
    }

    /**
     * 写入日志文件内容
     * @param $string
     */
    public function log($string)
    {
        $this->fwrite($string . "\n");
    }

    /**
     * 写入错误格式的日志内容
     * @param $string
     */
    public function errorLog($string)
    {
        $this->log(self::COLOR_SEQ . "1;37m" . "!! WARNING: " . $string . self::RESET_SEQ);
    }

    public function logQuery($query, $class_name = null, $parse_time = 0, $action = 'Load')
    {
        $class_name = $class_name ? : 'Sql';
        $buffer = self::COLOR_SEQ . self::PURPLE . "$class_name $action ("
            . number_format($parse_time * 1000, '4')
            . "ms)  " . self::RESET_SEQ . self::COLOR_SEQ . self::WHITE
            . $query . self::RESET_SEQ;

        $this->log($buffer);
    }

    /**
     * 日志输出Request对象内容
     * @param $request
     */
    public function logRequest($request)
    {
        $this->log("Processing "
            . $request->getModuleName() . '\\'
            . $request->getControllerName()
            . "Controller#"
            . $request->getActionName()
            . " (for {$request->getServer('REMOTE_ADDR')}"
            . " at " . date('Y-m-d H:i:s') . ")"
            . " [{$request->getMethod()}]"
        );
        $params = array();
        $params = array_merge($params,
            $request->getParams(),
            $request->getPost(),
            $request->getFiles(),
            $request->getQuery()
        );
        $this->log("Parameters: " . print_r($params, true));
    }

    /**
     * 日志输出异常信息内容
     * @param $exception
     */
    public function logException($exception)
    {
        $this->log(
            get_class($exception) . ": "
            . $exception->getMessage()
            . " in file "
            . $exception->getFile()
            . " at line "
            . $exception->getLine()
        );
        $this->log($exception->getTraceAsString());
    }
}
