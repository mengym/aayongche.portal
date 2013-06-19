<?php

class AuthenticationController extends ApplicationController
{
    protected $layout = 'simple';

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {

    }

    public function logoutAction()
    {
        $this->isJsonResponse();
        $session = Yaf\Session::getInstance();
        $session->__unset('user_id');
        $session->__unset('user_name');
        $session->__unset('user_sex');
        $this->redirect("/");
    }
}
