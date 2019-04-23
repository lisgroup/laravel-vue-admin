<?php

namespace App\Http\Controllers\Bus;

use App\Http\Repository\AccessToken;
use App\Http\Repository\ToolRepository;
use Curl\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Qiniu\Auth;

class AutoController extends Controller
{
    /**
     * 获取七牛 Token 的方法
     *
     * @return \Illuminate\Http\Response
     */
    public function getToken()
    {
        $bucket = env('QINIU_BUCKET');
        $accessKey = env('QINIU_ACCESSKEY');
        $secretKey = env('QINIU_SECRETKEY');
        $url = env('APP_URL');

        $auth = new Auth($accessKey, $secretKey);
        //$upToken = $auth->uploadToken($bucket);
        // 上传策略
        $policy = array(
            'deadline' => time() + 60, // 上传凭证有效截止时间。Unix时间戳，单位为秒
            'returnUrl' => $url.'/api/qiniuCallback',
            'returnBody' => '{"key": $(key)}',
            // 'callbackBody' => 'key=$(key)&hash=$(etag)&bucket=$(bucket)',
        );
        $upToken = $auth->uploadToken($bucket, null, 3600, $policy);

        return $this->out(200, ['token' => $upToken]);
    }

    /**
     * 七牛上传回调方法
     *
     * @param Request $request
     *
     * @return array
     */
    public function qiniuCallback(Request $request)
    {
        $uploadRet = $request['upload_ret'];
        $ret = base64_decode($uploadRet);
        $cbody = json_decode($ret, true);
        if (empty($cbody['key'])) {
            return ['code' => 1, 'data' => [], 'msg' => 'error'];
        }
        // 七牛云访问的 url
        $dn = env('QINIU_URL');
        Log::info('qiniuCallback: ', $cbody); // error_log(print_r($cbody, true));
        $url = $dn.$cbody['key'];

        $stat_ = file_get_contents($url.'?stat');
        $stat = json_decode($stat_, true);
        $mtype = $stat['mimeType'];
        if (substr($mtype, 0, 6) == 'image/') {
            // 3. 调用百度 OCR 识别信息
            $words = $this->baiduOCR('', $url);
            return ['code' => 0, 'data' => ['url' => $url, 'words' => $words], 'msg' => 'success'];
        }
        return ['code' => 1, 'data' => [], 'msg' => 'error'];
    }

    /**
     * @param $img
     * @param $imgUrl
     *
     * @return string
     */
    public function baiduOCR($img, $imgUrl)
    {
        // 1. 获取 BaiDu Access Token
        $accessToken = AccessToken::getInstance()->getBaiDuToken();

        if (empty($accessToken)) {
            Log::error('######### GET baiduOCR $accessToken ERROR !!!'); //error_log($e->getMessage());
            return '';
        }

        // 2. 识别图片
        $url = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general?access_token='.$accessToken;
        // 2.1 两种传递方式：一、base64 文件， 二、网络图片地址
        if ($img) {
            $img = base64_encode($img);
            $bodys = ["image" => $img];
        } elseif ($imgUrl) {
            $bodys = ['url' => $imgUrl];
        } else {
            return '';
        }

        $http = new Http();
        $res = $http->request($url, $bodys, 'POST');

        $result = json_decode($res['content'], true);

        $words = '';
        if ($result) {
            foreach ($result['words_result'] as $value) {
                $words .= $value['words'].PHP_EOL;
            }

            // 3. 保存到数据库
            $upload = new \App\Models\Upload();
            $upload->img_url = $imgUrl;
            $upload->content = $words;
            return $upload->save();
        }
        return $words;
    }
}
