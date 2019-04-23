<?php
/**
 * Token 获取并缓存到文件
 * User: lisgroup
 * Date: 18-10-07
 * Time: 上午11:39
 */

namespace App\Http\Repository;


use Curl\Http;
use Illuminate\Support\Facades\Cache;

class AccessToken
{
    /**
     * @var int 默认缓存 30 天
     */
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

    private static $instance;

    /**
     * TokenUtil constructor.
     * @param array $config
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
    public function getBaiDuToken($bool = false)
    {
        $bool && Cache::forget('baidu_access_token');

        return Cache::remember('baidu_access_token', $this->expiresIn, function() {
            $url = env('BAIDU_token_url') ?? 'https://aip.baidubce.com/oauth/2.0/token';
            $curl = new Http();
            $params = [
                'api' => env('BAIDU_api'),
                'grant_type' => env('BAIDU_grant_type'),
                'client_id' => env('BAIDU_client_id'),
                'client_secret' => env('BAIDU_client_secret'),
            ];

            $httpResult = $curl->request($url, $params, 'post', 5, $this->header);

            // 2. 写入缓存
            if (!empty($httpResult['content'])) {
                $content = json_decode($httpResult['content'], true);
                return $content['access_token'] ?? '';
            } else {
                return '';
            }
        });
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
                $this->getBaiDuToken();
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
            sleep($interval); // 等待时间，进行下一次操作。
        } while (true);
    }

}
