<?php

/**
 * The main application controller.
 *
 * All application controllers may inherit from this controller.
 * This controller uses Layout class (@see lib/Layout.php)
 */
class ApplicationController extends Yaf\Controller_Abstract
{
    /**
     * The name of layout file.
     *
     * The name of layout file to be used for this controller ommiting extension.
     * Layout class will use extension from application config ini.
     *
     * @var string
     */
    protected $layout;

    /**
     * The session instance.
     *
     * Yaf\Session instance to be used for this application.
     *
     */
    protected $session;

    /**
     * A Yaf\Config\Ini object that contains application configuration data.
     *
     * @var Yaf\Config\Ini
     */
    private $config;

    /**
     * Initialize layout and session.
     *
     * In this method can be initialized anything that could be usefull for
     * the controller.
     *
     * @return void
     */
    public function init()
    {
        // Set the layout.
        $this->getView()->setLayout($this->layout);

        //Set session.
        $this->session = Yaf\Session::getInstance();

        // Assign session to views too.
        $this->getView()->session = $this->session;

        // Assign application config file to this controller
        $this->config = Yaf\Application::app()->getConfig();

        // Assign config file to views
        $this->getView()->config = $this->config;
    }

    /**
     * When assign a public property to controller, this property will be
     * available to action view template too.
     *
     * @param string $name  the name of the property
     * @param mixed $value the value of the property
     *
     * @return void
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
        $this->getView()->assignRef($name, $value);
    }

    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Cancel current action proccess and forward to {@link notFound()} method.
     *
     * @return false
     */
    public function forwardTo404()
    {
        $this->forward('Index', 'application', 'notFound');
        $this->getView()->setScriptPath($this->getConfig()->application->directory
        . "/views");
        header('HTTP/1.0 404 Not Found');
        return false;
    }

    /**
     * Renders a 404 Not Found template view
     *
     * @return void
     */
    public function notFoundAction()
    {
    }

    /**
     * 指定返回的Response类型为Json
     */
    public function isJsonResponse()
    {
        Yaf\Dispatcher::getInstance()->autoRender(FALSE);
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json');
    }

    /**
     * 初始化数据库连接
     */
    protected function initDbConnection()
    {
        require_once(APP_PATH . '/app/models/ActiveRecord.php');
        ActiveRecord\Config::initialize(function ($cfg) {
            $cfg->set_model_directory(APP_PATH . '/app/models');
            $cfg->set_connections(array('main' => 'mysql://root:@localhost/aayongche_main;charset=utf8'));
            $cfg->set_default_connection('main');
        });
        require_once(APP_PATH . '/app/models/User.php');
    }
}
