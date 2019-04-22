<?php
/**
 * Token 获取并缓存到文件
 * User: lisgroup
 * Date: 18-10-07
 * Time: 上午11:39
 */

namespace App\Http\Repository;


use Curl\Http;
use Illuminate\Support\Facades\Storage;

class AccessToken
{
    protected $expiresIn = 2592000;

    /**
     * @var array 请求的 header 头信息
     * 如：
     * $header = [
     * 'Content-Type: application/json;charset=UTF-8',
     * 'Content-Length: 46', // 需定义长度 strlen($params),
     * 'user-agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1',
     * ]
     *
     */
    protected $header = [];

    /**
     * @var string|array  模拟表单提交的数据,发送 JSON 数据必须定义为 String
     * 如：{"username":"admin","password":"123456"}
     */
    protected $params = 'api=11959004&grant_type=client_credentials&client_id=yxGSiFRIbfdl7WwGGXIGlmnR&client_secret=nE2rXH6ScEUg8MifDPbTzCrD5SHoC4vh';

    private $fileName = '';

    private $url;
    private static $instance;

    /**
     * TokenUtil constructor.
     * @param array $config
     *
     * $config = [
     *      'url' => "https://api.yii2.wang/v1/authorize",
     *      'expiresIn' => '86400', // 24H
     *      'params' => ['username' => '13776036576', 'password' => '123456'],
     * ];
     */
    private function __construct($config)
    {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
        // empty($this->fileName) && $this->fileName = __DIR__.'/token.txt';
    }

    public static function getInstance($config = [])
    {
        if (!(self::$instance instanceof AccessToken)) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    /**
     * 读取 token
     * @param bool $bool 是否强制刷新缓存 token
     * @return bool|string
     */
    public function getToken($bool = false)
    {
        try {
            $data = Storage::get('token.txt');
        } catch (\Exception $e) {
            return $this->buildAccessToken();
        }
        $store = json_decode($data, true);
        // 1. 强制获取，文件不存在，文件过期 =》 刷新 Token
        if ($bool || empty($store['create_at']) || time() - $store['create_at'] > $this->expiresIn) {
            // HTTP 请求获取 token 并写入文件缓存
            return $this->buildAccessToken();
        }

        // 2. 在有效期内,直接读取返回
        return $store['access_token'];
    }

    /**
     * 设置定时器，每两小时执行一次 buildAccessToken() 函数获取一次 access_token
     */
    public function setInterval()
    {
        ignore_user_abort();//关闭浏览器仍然执行
        set_time_limit(0);//让程序一直执行下去
        $interval = $this->expiresIn;//每隔一定时间运行
        do {
            try {
                $this->buildAccessToken();
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
            sleep($interval); // 等待时间，进行下一次操作。
        } while (true);
    }

    /**
     * 获取 access_token 并保存到 token.txt 文件中
     * @return string|bool 返回 access_token
     */
    private function buildAccessToken()
    {
        if (empty($this->url)) {
            return false;
        }

        $curl = new Http();
        $httpResult = $curl->request($this->url, $this->params, 'post', 6, $this->header);

        // 2. 写入文件缓存
        if (!empty($httpResult['content'])) {
            $content = json_decode($httpResult['content'], true);
            $response = array_merge($content, ['create_at' => time()]);
            $json = json_encode($response, JSON_UNESCAPED_UNICODE);

            // 2018-10-11 使用 Laravel Storage 存储数据
            return Storage::disk('local')->put('token.txt', $json) ? $content['access_token'] : false;
        } else {
            return false;
        }
    }
}
