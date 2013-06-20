<?php

class IndexController extends ApplicationController 
{
    protected $layout = 'simple';

    public function indexAction() 
    {
        $this->heading = 'Home Page';
    }
}
