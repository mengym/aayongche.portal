<?php
/**
 * Sets an authenticity token to session and validates it against POST
 * submissions.
 *
 * To enable it set it On at config/application.ini file
 * <code>
 * application.protect_from_csrf=1
 * </code>
 *
 * Then you must define an input hidden field in each html form you submit.
 * <code>
 * <input type="hidden" name="_auth_token" value="<?php echo Yaf\Session::getInstance()->auth_token ?>">
 * </code>
 *
 * After submission of the form, the plugin will attempt to validate the
 * auth_token an will throw an \Exception if tokens are not equal.
 */
class AuthTokenPlugin extends Yaf\Plugin_Abstract
{
    /**
     * 路由结束之后触发
     * 此路由必须正确完成才触发
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     */
    public function routerShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
        $this->auth_token();
    }

    /**
     * 分发循环开始之前被触发
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     */
    public function dispatchLoopStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {

        $this->verify_auth_token($request);
    }

    /**
     * 验证认证句柄是否正确，如果验证成功则重新生成认证句柄
     * 验证判断前提是　　是否打开csrf攻击防护，且当前请求是否是POST请求
     *
     * 验证判断依据为
     * １　必须是POST请求
     * ２　POST请求必须包含参数 '_auth_token'
     * ３  POST参数'_auth_token'与当前认证随即数相同
     * 如果不满足以上条件则抛出　Exception('Invalid authenticity token!');
     * @param $request
     * @throws Exception
     */
    protected function verify_auth_token($request)
    {
        $config = Yaf\Application::app()->getConfig();

        if ($config['application']['protect_from_csrf'] && $request->isPost()) {

            $post = $request->getPost();

            if (!isset($post['_auth_token']) || $post['_auth_token'] !== $this->auth_token()) {
                throw new \Exception('Invalid authenticity token!');
            } else {
                $session = Yaf\Session::getInstance();
                $session->auth_token = NULL;
                $this->auth_token();
            }
        }
    }

    /**
     * 获取当前认证随机数，如果为空则产生新的随机数放到认证句柄中
     * @return string
     */
    protected function auth_token()
    {
        $session = Yaf\Session::getInstance();
        $session->auth_token = $session->auth_token
            ? : base64_encode(sha1(uniqid(rand(), true)));
        return $session->auth_token;
    }

}
