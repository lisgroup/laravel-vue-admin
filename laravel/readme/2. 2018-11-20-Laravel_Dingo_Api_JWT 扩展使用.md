 # Restful Api with JWT
 
 ## 安装和配置
 
```
composer install
cp .env.example .env
php artisan key:generate
```

全新 laravel 需要 
```
composer require dingo/api:2.0.0-alpha2
composer require tymon/jwt-auth 1.*@rc
```


1. 在 `.env` 文件中配置 mysql 用户名密码及数据库信息
 
2. 在 `.env` 添加 dingo\api 配置项
```shell

# API 相关配置
API_STANDARDS_TREE=vnd  # 公开的及商业项目用 vnd
API_SUBTYPE=my_api  # 项目简称
API_PREFIX=api  # 前缀
API_VERSION=v1  # 不提供版本时使用的版本号
API_NAME="Laravel Api Demo"  # 使用 API Blueprint 命令生成文档的时候才用到
API_STRICT=false # Strict 模式要求客户端发送 Accept 头而不是默认在配置文件中指定的版本，这意味着你不能通过Web浏览器浏览API
API_DEFAULT_FORMAT=json
API_DEBUG=true # 开启 debug 模式
# php artisan jwt:secret
JWT_SECRET=R9EF3iqvd9OUJe3b8GGDo3bcdJASBVp8

```
3. 最后运行如下命令
```shell
php artisan vendor:publish --provider="Dingo\Api\Provider\LaravelServiceProvider"

php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

php artisan jwt:secret
php artisan migrate
php artisan db:seed
php artisan serve
```

## 使用方法

1. 注册：
url: hostname/api/register/
method: post
param: email,name,password
response: json(token,message,status_code)

2. 登录：
url: hostname/api/login/
method: post
param: email,password
response: json(token,message,status_code)
restfulApi(use these api should add the token in header first)

3. 查看：
url: hostname/api/user/
method: get,post,put,delete

4. 改变需求参考文件：
controller: App\Http\Api\
route: routes\api.php