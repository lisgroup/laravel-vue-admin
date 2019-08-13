<?php

namespace App\Http\Controllers\Bus;

use App\Http\Repository\AccessToken;
use AuroraLZDF\Bigfile\Traits\TraitBigfileChunk;
use Curl\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Qiniu\Auth;

class AutoController extends Controller
{
    use TraitBigfileChunk;

    /**
     * @var Http 请求类
     */
    private $http;

    public function __construct()
    {
        $this->http || $this->http = Http::getInstent();
    }

    /**
     * 展示视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function loadView()
    {
        $data = [
            'chunk_size' => config('bigfile.chunk_size'),
            'max_size' => config('bigfile.max_size'),
            'user_id' => Auth()->id(),
        ];
        return view('bigfile.bigfile', compact('data'));
    }

    /**
     * 上传操作
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function upload(Request $request)
    {
        $data = $request->all();
        $action = $request->input('act');

        if (isset($action) && $action == 'upload') {

            $result = $this->uploadToServer($data);
            return response()->json($result);
        }

        $result = $this->uploadChunk($request);
        return response()->json($result);
    }

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
     * @return \Illuminate\Http\Response
     */
    public function qiniuCallback(Request $request)
    {
        $uploadRet = $request['upload_ret'];
        $ret = base64_decode($uploadRet);
        $cbody = json_decode($ret, true);
        if (empty($cbody['key'])) {
            // return ['code' => 1, 'data' => [], 'msg' => 'error'];
            return $this->out(1100);
        }
        // 七牛云访问的 url
        $dn = env('QINIU_URL');
        Log::info('qiniuCallback: ', $cbody); // error_log(print_r($cbody, true));
        $url = $dn.$cbody['key'];

        // $stat_ = file_get_contents($url.'?stat');
        // $stat = json_decode($stat_, true);

        $stat_get = $this->http->get($url.'?stat', [], 4);
        $stat = json_decode($stat_get['content'], true);

        if ($stat && $stat['mimeType'] && substr($stat['mimeType'], 0, 6) == 'image/') {
            // 3. 调用百度 OCR 识别信息
            $words = $this->baiduOCR('', $url);
            return $this->out(200, ['url' => $url, 'words' => $words]);
        }

        return $this->out(1100);
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

        // $http = new Http();
        $res = $this->http->request($url, $bodys, 'POST');

        $result = json_decode($res['content'], true);

        $words = '';
        if ($result && isset($result['words_result'])) {
            foreach ($result['words_result'] as $value) {
                $words .= $value['words'].PHP_EOL;
            }

            // 3. 保存到数据库
            $upload = new \App\Models\Upload();
            $upload->img_url = $imgUrl;
            $upload->content = $words;
            $upload->save();
        }
        return $words;
    }
}
