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

    /**
     * 用户登录接口
     * 返回success = 1 为成功
     */
    public function loginAction()
    {
        $post = $this->getRequest()->getPost();
        $this->initDbConnection();
        $user = User::all(array('conditions' => array('mobile = ? and password = ?', $post['mobile'], md5($post['password']))));
        $result = array("success" => count($user));
        if (count($user) > 0) {
            $this->genLoginSession($user);
        }
        $this->isJsonResponse();
        $this->getResponse()->setBody(json_encode($result));
    }

    public function logoutAction()
    {
        $this->noRender();
        $this->logoutSession();
        $this->redirect("/");
    }

    /**
     * 验证
     * 1 验证用户手机号是否已注册
     * 2 检测验证码是否正确
     */
    public function validateAction()
    {
        $query = $this->getRequest()->getQuery();
        if (array_key_exists('code', $query)) {
            $session = Yaf\Session::getInstance();
            $result = array("success" => $session->code == $query['code'] ? 1 : 0);
        }
        //echo $mobile;
        $this->isJsonResponse();
        $this->getResponse()->setBody(json_encode($result));
    }
}
