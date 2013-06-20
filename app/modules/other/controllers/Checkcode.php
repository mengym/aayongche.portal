<?php

class CheckcodeController extends ApplicationController
{
    //protected $layout = 'main';

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $this->noRender();
    }

    public function genCheckCodeImgAction()
    {
        $this->noRender();
        $_vc = new \Helper\ValidateCode(); //实例化一个对象
        $_vc->doimg();
        //验证码保存到SESSION中
        $session = Yaf\Session::getInstance();
        $session->code = $_vc->getCode();
    }
}
