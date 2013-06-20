<?php


class RegisterController extends ApplicationController
{
    protected $layout = 'simple';

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {

    }

    public function userRegisterAction()
    {
        $post = $this->getRequest()->getPost();
        $this->initDbConnection();
        $user = new User();
        $user->mobile = $post['mobile'];
        $user->password = md5($post['password']);
        $user->name = $post['name'];
        $user->sex = $post['sex'];
        $user->regist_time = time();
        $user->save();
        $user = User::all(array('conditions' => array('mobile = ? and password = ?', $post['mobile'], md5($post['password']))));
        if (count($user) > 0) {
            $this->genLoginSession($user);
        }
        $this->noRender();
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
        if (array_key_exists('mobile', $query)) {
            $this->initDbConnection();
            $user = User::all(array('conditions' => array('mobile = ?', $query['mobile'])));
            $result = array("success" => count($user));
        } else if (array_key_exists('code', $query)) {
            $session = Yaf\Session::getInstance();
            $result = array("success" => $session->code == $query['code'] ? 1 : 0);
        }
        //echo $mobile;
        $this->isJsonResponse();
        $this->getResponse()->setBody(json_encode($result));
    }

    public function genCheckcodeAction()
    {
        $session = Yaf\Session::getInstance();
        $session->code = rand(100000, 999999);
        $this->isJsonResponse();
        $this->getResponse()->setBody($session->code);
    }
}
