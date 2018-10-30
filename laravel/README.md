## 安装方法：
为了方便自己使用，已经讲打包好的代码放到了 php/public 目录下。即正常部署时候，只需要配置后端 php 环境即可。

1. 安装 php 环境 (必须)
```php
git clone https://gitee.com/lisgroup/vueBus.git
cd vueBus/laravel
composer install
cp .env.example .env
```

2. 配置项修改 .env 文件数据库
```php
# 修改数据库配置
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test
DB_USERNAME=root
DB_PASSWORD=root

# 如 redis 可用建议修改
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

3. 启动 laravels 服务监听 5200 端口
```php
php artisan laravels start -d
```
更多细节参考：[https://github.com/hhxsv5/laravel-s/blob/master/README-CN.md](https://github.com/hhxsv5/laravel-s/blob/master/README-CN.md)

## 域名绑定
域名需要绑定到根目录，即项目的 php/public 目录下。

### nginx 配置参考：
```php
#gzip on;
#gzip_min_length 1024;
#gzip_comp_level 2;
#gzip_types text/plain text/css text/javascript application/json application/javascript application/x-javascript application/xml application/x-httpd-php image/jpeg image/gif image/png font/ttf font/otf image/svg+xml;
#gzip_vary on;
#gzip_disable "msie6";
upstream laravels {
    # By IP:Port
    server 127.0.0.1:5200 weight=5 max_fails=3 fail_timeout=30s;
    # By UnixSocket Stream file
    #server unix:/xxxpath/laravel-s-test/storage/laravels.sock weight=5 max_fails=3 fail_timeout=30s;
    #server 192.168.1.1:5200 weight=3 max_fails=3 fail_timeout=30s;
    #server 192.168.1.2:5200 backup;
}
server {
    listen 80;
    # 别忘了绑Host哟
    server_name www.bus.com;
    root /home/www/vueBus/laravel/public;
    access_log /home/wwwlogs/nginx/$server_name.access.log;
    autoindex off;
    index index.html index.htm;
    # Nginx处理静态资源(建议开启gzip)，LaravelS处理动态资源。
    location / {
        try_files $uri $uri/index.html @laravels;
    }
    # 当请求PHP文件时直接响应404，防止暴露public/*.php
    #location ~* \.php$ {
    #    return 404;
    #}
    location @laravels {
        proxy_http_version 1.1;
        # proxy_connect_timeout 60s;
        # proxy_send_timeout 60s;
        # proxy_read_timeout 120s;
        proxy_set_header Connection "keep-alive";
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Real-PORT $remote_port;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header Host $http_host;
        proxy_set_header Scheme $scheme;
        proxy_set_header Server-Protocol $server_protocol;
        proxy_set_header Server-Name $server_name;
        proxy_set_header Server-Addr $server_addr;
        proxy_set_header Server-Port $server_port;
        proxy_pass http://laravels;
    }
}
```


## 使用方法
浏览器访问绑定的域名即可查看

在输入框输入查询的公交车，（如：快1）点击搜索后，会出现搜索到的车次，再次点击需要查询车次的方向，即可查看实时公交状态。

## 开发记录

1. 创建一个 Crons 表迁移和模型
```php
php artisan make:model Models/Cron -m
```

2. 添加表的相应字段

/database/migrations/2018_10_27_151143_create_crons_table.php
```
/**
 * Run the migrations.
 *
 * @return void
 */
public function up()
{
    Schema::create('crons', function (Blueprint $table) {
        $table->increments('id');
        $table->string('line_info')->default('')->comment('班次');
        $table->mediumText('content')->comment('内容');
        $table->timestamps();
    });
}
```
2.1 修改 `.env` 数据库配置项；
```
DB_DATABASE=test
DB_USERNAME=root
DB_PASSWORD=root
```
2.2 运行迁移生成表;
```
php artisan migrate
```
2.3 创建填充文件;
```
php artisan make:seed CronTasksTableSeeder
```
2.4 生成测试数据;
```
public function run()
{
    DB::table('articles')->insert([
        [
            'title' => 'TNTSearch',
            'content' => '一个用PHP编写的功能齐全的全文搜索引擎'
        ],
        [
            'title' => 'jieba-php',
            'content' => '"结巴"中文分词:做最好的php中文分词、中文断词组件'
        ]
    ]);
}
```
运行填充；
```
php artisan db:seed --class=ArticlesTableSeeder
```

3. 添加路由
```
/routes/web.php
```
<?php
use App\Models\Cron;

Route::get('search', function () {
    // 为查看方便都转成数组
    dump(Cron::all()->toArray());
});
```


## 待完成工作
1. 查询的公交线路存入数据库保存。（目前保存在文件中）

