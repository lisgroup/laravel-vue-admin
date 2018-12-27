<?php

namespace App\Exceptions;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // 参数验证错误的异常，我们需要返回 400 的 http code 和一句错误信息 1104
        if ($exception instanceof ValidationException) {
            // $code = 1104;
            // return response(['error' => array_first(array_collapse($exception->errors()))], 400);
            return $this->getResultByCode(1104, array_first(array_collapse($exception->errors())));
        }
        // 用户认证的异常，我们需要返回 401 的 http code 和错误信息
        if ($exception instanceof UnauthorizedHttpException) {
            // return response($exception->getMessage(), 401);
            $this->getResultByCode(401, $exception->getMessage());
        }

        if ($exception instanceof AuthenticationException) {
            return $this->getResultByCode(1200);
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->getResultByCode(404);
        }

        if ($exception instanceof QueryException) {
            // return response()->json(['code' => '500', 'reason' => 'Internal Server Error', 'data' => ''], 500);
        }

        // JWT exception
        if ($exception instanceof TokenBlacklistedException) {
            return $this->getResultByCode(5001);
        }

        // 2018-12-27 Missing404Exception 未生成全文索引的错误
        // if ($exception instanceof Missing404Exception) {
        //     return $this->getResultByCode(5002);
        // }

        return parent::render($request, $exception);
    }

    /**
     * 根据 code 码获取返回 json 数据
     *
     * @param int $code
     * @param string $reason
     * @param int $httpStatusCode
     *
     * @return \Illuminate\Http\Response
     */
    public function getResultByCode($code = 200, $reason = 'success', $httpStatusCode = 200)
    {
        if ($reason === 'success') {
            $reason = config('errorCode.'.$code.'.reason') ?? 'error';
        }

        return response()->json(['code' => $code, 'reason' => $reason, 'data' => ''], $httpStatusCode)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
