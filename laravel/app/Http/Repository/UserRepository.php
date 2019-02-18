<?php
/**
 * Desc: User 仓库类
 * User: lisgroup
 * Date: 18-12-22
 * Time: 下午8:52
 */

namespace App\Http\Repository;


use App\Http\Service\GeetestLib;
use App\User;
use Illuminate\Support\Facades\Cache;

// use App\User;

class UserRepository
{
    /**
     * @var self $instance 单例
     */
    private static $instance;
    // private $userModel;
    private $config;

    /**
     * 获取实例方法
     *
     * @param array $conf
     *
     * @return UserRepository
     */
    public static function getInstent($conf = [])
    {
        if (!isset(self::$instance)) {
            self::$instance = new static($conf);
        }
        return self::$instance;
    }

    /**
     * 极验开始获取验证码操作
     * @param $input
     * @return mixed
     */
    public function startCaptcha($input)
    {
        $id = env('GEE_CAPTCHA_ID');
        $privateKey = env('GEE_PRIVATE_KEY');

        if (empty($id) || empty($privateKey) || empty($input['uuid'])) {
            return ['success' => 0, 'gt' => '', 'challenge' => '', 'new_captcha' => 0,];
        }
        $data = array(
            "user_id" => $input['uuid'], // 网站用户id
            "client_type" => "web", // web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => "127.0.0.1" // 请在此处传输用户请求验证时所携带的IP
        );
        $gtLib = new GeetestLib($id, $privateKey);
        $status = $gtLib->pre_process($data, 1);

        Cache::add('Gee_gtserver_'.$input['uuid'], $status, 1644);
        // Cache::add('Gee_user_id_'.$input['uuid'], 1, 1644);
        // $_SESSION['gtserver'] = $status;
        // $_SESSION['user_id'] = $data['user_id'];
        return $gtLib->get_response();
    }

    /**
     * 极验校验验证码操作
     *
     * @param $input
     *
     * @return bool
     */
    public function verifyCaptcha($input)
    {
        $id = env('GEE_CAPTCHA_ID');
        $privateKey = env('GEE_PRIVATE_KEY');
        $GtSdk = new GeetestLib($id, $privateKey);
        if (empty($input['uuid']) || empty($input['geetest_challenge']) || empty($input['geetest_validate']) || empty($input['geetest_seccode'])) {
            // return '{"status":"fail"}';
            return false;
        }

        $data = array(
            "user_id" => $input['uuid'], // 网站用户id
            "client_type" => "web", // web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => "127.0.0.1" // 请在此处传输用户请求验证时所携带的IP
        );

        if (Cache::get('Gee_gtserver_'.$input['uuid']) == 1) {   // 服务器正常
            $result = $GtSdk->success_validate($input['geetest_challenge'], $input['geetest_validate'], $input['geetest_seccode'], $data);
        } else {  // 服务器宕机,走 failback 模式
            $result = $GtSdk->fail_validate($input['geetest_challenge'], $input['geetest_validate'], $input['geetest_seccode']);
        }
        // return $result ? '{"status":"success"}' : '{"status":"fail"}';
        return $result;
    }

    public function changePassword($input)
    {
        if (empty($input['old_pwd']) || empty($input['password']) || empty($input['repassword'])) {
            return ['code' => 1104];
        }
        // 0. 校验新密码和重复密码是否一致
        if ($input['password'] != $input['repassword']) {
            return ['code' => 1213,'reason' => '重复密码和新密码不一致'];
        }

        // 1. 先获取个人信息
        $user = $userInfo = auth('api')->user();
        // $user['name'] = 'admin';
        // 2. 验证用户名密码
        $credentials = ['name' => $user['name'], 'password' => $input['old_pwd']];
        if (!$token = auth('api')->attempt($credentials)) {
            return ['code' => 4005, 'reason' => '旧密码输入有误'];
        }
        // 3. 修改密码
        // $info = User::findOrFail(['name' => $user['name']]);
        $encrypt = bcrypt($input['password']);
        $result = User::where('name', $user['name'])->update(['password' => $encrypt]);
        if ($result) {
            auth('api')->logout();
            return ['code' => 200, 'data' => [], 'reason' => 'success'];
        }
        return ['code' => 4001, 'data' => []];
    }

    private function __construct($config)
    {
        !empty($config) && ($this->config || $this->config = $config);
        // $this->userModel || $this->userModel = new User();
    }

    /**
     * 不允许 clone
     */
    private function __clone()
    {

    }
}
