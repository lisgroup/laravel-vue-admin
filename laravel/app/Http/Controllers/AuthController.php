<?php

namespace App\Http\Controllers;


// use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Controller;
use App\Events\LoginEvent;
use App\Events\LoginSwooleEvent;
use App\Http\Repository\UserRepository;
use Hhxsv5\LaravelS\Swoole\Task\Event;
use Jenssegers\Agent\Agent;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     * 要求附带email和password（数据来源users表）
     *
     * @return void
     */
    public function __construct()
    {
        // 这里额外注意了：官方文档样例中只除外了『login』
        // 这样的结果是，token 只能在有效期以内进行刷新，过期无法刷新
        // 如果把 refresh 也放进去，token 即使过期但仍在刷新期以内也可刷新
        // 不过刷新一次作废
        $except = ['login', 'refresh', 'startCaptcha', 'verifyCaptcha'];
        $this->middleware('auth:api', ['except' => $except]);
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\Response|array
     */
    public function login()
    {
        $input = request()->all();
        // 1. 验证 geetest
        $result = (UserRepository::getInstent())->verifyCaptcha($input);
        if (!$result || empty($input['username']) || empty($input['password'])) {
            return $this->out(1207);
        }

        // 2. 验证用户名密码
        $credentials = ['name' => $input['username'], 'password' => $input['password']];
        if (!$token = auth('api')->attempt($credentials)) {
            return $this->out(4001);
        }
        // return $this->respondWithToken($token);

        // 登录成功，触发事件
        // 如果是 cli 模式使用 laravels Task 异步事件
        if (PHP_SAPI == 'cli' && extension_loaded('swoole')) {
            Event::fire(new LoginSwooleEvent(auth('api')->user(), new Agent(), \Request::getClientIp(), time()));
        } else {
            event(new LoginEvent(auth('api')->user(), new Agent(), \Request::getClientIp(), time()));
        }

        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
        return $this->out(200, $data);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\Response
     */
    public function info()
    {
        // return response()->json(auth('api')->user());
        // 输出管理员 roles
        $userInfo = auth('api')->user();
        $roles = $userInfo->roles->map(function($item) {
            return $item->name;
        });
        $data = [
            'roles' => $roles,
            'name' => $userInfo['name'],
            // 'avatar' => 'https://note.youdao.com/favicon.ico',
            'avatar' => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
        ];
        return $this->out(200, $data);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        auth('api')->logout();

        // return response()->json(['message' => 'Successfully logged out']);
        return $this->out(200, 'success');
    }

    /**
     * Refresh a token.
     * 刷新token，如果开启黑名单，以前的token便会失效。
     * 值得注意的是用上面的getToken再获取一次Token并不算做刷新，两次获得的Token是并行的，即两个都可用。
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminLogin()
    {
        $credentials = request(['email', 'password']);

        $where = array_merge($credentials, ['is_admin' => 1]);
        if (!$token = auth('admin')->attempt($where)) {
            return response()->json(['reason' => 'Unauthorized', 'code' => 4001]);
        }

        return $this->respondWithToken($token);
    }

    /**
     * 1. 极验开始获取验证码操作
     */
    public function startCaptcha()
    {
        $input = request(['uuid', '_t']);
        $data = (UserRepository::getInstent())->startCaptcha($input);
        return $this->out(200, $data);
    }

    /**
     * 2. 极验校验验证码操作
     */
    public function verifyCaptcha()
    {
        $input = request();
        $result = (UserRepository::getInstent())->verifyCaptcha($input);
        $data['status'] = $result ? 'success' : 'fail';

        return $this->out(200, $data);
    }

}
