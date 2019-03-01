1. Q1. (E_RECOVERABLE_ERROR)
   Argument 2 passed to Lcobucci\JWT\Signer\Hmac::createHash() must be an instance of Lcobucci\JWT\Signer\Key, null given, called in /www/bus/laravel/vendor/lcobucci/jwt/src/Signer/BaseSigner.php on line 34

A1: 参考：[请求 weapp/authorizations 接口 JWT 报错了？](https://laravel-china.org/topics/12857/request-weappauthorizations-interface-jwt-wrong#reply1)
执行 ` php artisan jwt:secret `，在 env 文件中生成 jwt secret
