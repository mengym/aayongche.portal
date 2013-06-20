<?php

class TransactionController extends ApplicationController
{
    protected $layout = 'main';

    public function init()
    {
        parent::init();
        parent::checkLogin();
    }

    public function indexAction()
    {

    }
}
