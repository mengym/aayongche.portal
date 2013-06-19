<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

use eYaf\Request;
use eYaf\Response;
use eYaf\Layout;

class Bootstrap extends \Yaf\Bootstrap_Abstract
{
    /**
     * 初始化error句柄
     * @param \Yaf\Dispatcher $dispatcher
     */
    public function _initErrorHandler(Yaf\Dispatcher $dispatcher)
    {
        $dispatcher->setErrorHandler(array(get_class($this), 'error_handler'));
    }

    /**
     * 初始化配置文件
     * @param \Yaf\Dispatcher $dispatcher
     */
    public function _initConfig(Yaf\Dispatcher $dispatcher)
    {
        $this->config = Yaf\Application::app()->getConfig();
    }

    /**
     * 初始化Request过滤器
     * @param \Yaf\Dispatcher $dispatcher
     */
    public function _initRequest(Yaf\Dispatcher $dispatcher)
    {
        $dispatcher->setRequest(new Request());
    }

    /**
     * 初始化数据库连接
     * @param \Yaf\Dispatcher $dispatcher
     */
    public function _initDatabase(Yaf\Dispatcher $dispatcher)
    {

    }

    /**
     * 初始化插件
     * 包括(日志) (CSRF验证)
     * @param \Yaf\Dispatcher $dispatcher
     */
    public function _initPlugins(Yaf\Dispatcher $dispatcher)
    {
        $dispatcher->registerPlugin(new LogPlugin());

        $this->config->application->protect_from_csrf &&
        $dispatcher->registerPlugin(new AuthTokenPlugin());

    }

    public function _initLoader(Yaf\Dispatcher $dispatcher)
    {
    }

    /**
     * 初始化路由规则
     * @param \Yaf\Dispatcher $dispatcher
     */
    public function _initRoute(Yaf\Dispatcher $dispatcher)
    {
        $config = new Yaf\Config\Ini(APP_PATH . '/config/routing.ini');
        $dispatcher->getRouter()->addConfig($config);
    }


    /**
     * Custom init file for modules.
     *
     * Allows to load extra settings per module, like routes etc.
     */
    /**
     * 自定义modules　配置文件
     * 加载每一个modules的扩展配置文件
     * @param \Yaf\Dispatcher $dispatcher
     */
    public function _initModules(Yaf\Dispatcher $dispatcher)
    {
        $app = $dispatcher->getApplication();

        $modules = $app->getModules();
        foreach ($modules as $module) {
            if ('index' == strtolower($module)) continue;

            require_once $app->getAppDirectory() . "/modules" . "/$module" . "/_init.php";
        }
    }

    /**
     * 初始化页面整个模板
     * @param \Yaf\Dispatcher $dispatcher
     */
    public function _initLayout(Yaf\Dispatcher $dispatcher)
    {
        $layout = new Layout($this->config->application->layout->directory);
        $dispatcher->setView($layout);
    }

    /**
     * Custom error handler.
     *
     * Catches all errors (not exceptions) and creates an ErrorException.
     * ErrorException then can caught by Yaf\ErrorController.
     *
     * @param integer $errno   the error number.
     * @param string $errstr  the error message.
     * @param string $errfile the file where error occured.
     * @param integer $errline the line of the file where error occured.
     *
     * @throws ErrorException
     */
    public static function error_handler($errno, $errstr, $errfile, $errline)
    {
        // Do not throw exception if error was prepended by @
        //
        // See {@link http://www.php.net/set_error_handler}
        //
        // error_reporting() settings will have no effect and your error handler 
        // will be called regardless - however you are still able to read 
        // the current value of error_reporting and act appropriately. 
        // Of particular note is that this value will be 0 
        // if the statement that caused the error was prepended 
        // by the @ error-control operator.
        //
        if (error_reporting() === 0) return;

        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}
