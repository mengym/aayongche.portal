<?php

class IndexController extends ApplicationController
{
    protected $layout = 'main';

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $this->heading = "Login 123";
    }
}
