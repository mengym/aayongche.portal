<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

use eYaf\Logger;

class LogPlugin extends Yaf\Plugin_Abstract
{

    /**
     * 在路由之前触发
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     */
    public function routerStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
        Logger::startLogging();

        Logger::getLogger()->log("[{$request->getRequestUri()}]");
    }

    /**
     * 路由结束之后触发
     * 此路由必须正确完成才触发
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     */
    public function routerShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
        Logger::getLogger()->logRequest($request);
    }

    /**
     * 分发循环开始之前被触发
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     */
    public function dispatchLoopStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {

    }

    /**
     * 分发之前触发
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     */
    public function preDispatch(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {

    }

    /**
     * 分发结束之后触发
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     */
    public function postDispatch(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
    }

    /**
     * 分发循环结束之后触发
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     */
    public function dispatchLoopShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
        Logger::stopLogging();
    }


    /**
     * 在返回响应之前触发
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     */
    public function preResponse(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {

    }
}
