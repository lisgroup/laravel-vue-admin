<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
     * @return void
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
            $code = '1104';
            // $reason = config('errorCode.'.$code.'.reason');
            return response()->json(['code' => $code, 'reason' => array_collapse($exception->errors()), 'data' => ''])
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
            // return response(['error' => array_first(array_collapse($exception->errors()))], 400);
        }
        // 用户认证的异常，我们需要返回 401 的 http code 和错误信息
        if ($exception instanceof UnauthorizedHttpException) {
            return response($exception->getMessage(), 401);
        }

        // return parent::render($request, $exception);

        if ($exception instanceof AuthenticationException) {
            $code = '1200';
            $reason = config('errorCode.'.$code.'.reason');
            return response()->json(['code' => $code, 'reason' => $reason, 'data' => ''])
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'error' => 'Resource not found.'
            ], 404);
        }

        if ($exception instanceof QueryException) {
            // return response()->json(['code' => '500', 'reason' => 'Internal Server Error', 'data' => ''], 500);
        }

        return parent::render($request, $exception);
    }
}
