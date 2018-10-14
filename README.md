H5 自适应苏州实时公交查询系统
===============

> PHP 的运行环境要求 PHP7.0 以上。
1. PHP >= 7.0
2. PDO PHP Extension
3. MBstring PHP Extension
4. CURL PHP Extension

## 安装方法：
```php
git clone https://gitee.com/lisgroup/vueBus.git
cd php
composer install
```

## 域名绑定
域名需要绑定到根目录，即项目的 php/public 目录下。

Nginx 示例配置
```shell
server {
    listen 443;
    root /www/vueBus/php/public;
    server_name www.guke1.com; # 改为绑定证书的域名
    
    # ssl 配置
    ssl on;
    ssl_certificate /etc/bundle.crt; # 改为自己申请得到的 crt 文件的名称
    ssl_certificate_key /etc/my.key; # 改为自己申请得到的 key 文件的名称
    ssl_session_timeout 5m;
    ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
    ssl_ciphers ECDHE-RSA-AES128-GCM-SHA256:HIGH:!aNULL:!MD5:!RC4:!DHE;
    ssl_prefer_server_ciphers on;

    # 文件不存在 转发 index.php 处理
    location / {
        if (!-e $request_filename) {
            rewrite ^(.*)$ /index.php?s=/$1 last;
            break;
        }
    }
    
    location ~ [^/]\.php(/|$) {
        fastcgi_pass  unix:/tmp/php-cgi.sock;
        fastcgi_index index.php;
        # include fastcgi.conf;
        # PHP only, required if PHP built with --enable-force-cgi-redirect
        fastcgi_param REDIRECT_STATUS  200;
        # fastcgi_param PHP_ADMIN_VALUE "open_basedir=$documment_root/:/tmp/:/proc";
        fastcgi_param PHP_ADMIN_VALUE "open_basedir=$documment_root/../:/tmp/:/proc";

        fastcgi_split_path_info ^(.+?\.php)(/.*)$;
        set $path_info $fastcgi_path_info;
        fastcgi_param PATH_INFO  $path_info;
        try_files $fastcgi_script_name =404;
    }

    location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
    {
    	expires     30d;
    }

    location ~ .*\.(js|css)$
    {
    	expires     2d;
    }

    location ~ /.well-known
    {
    	allow all;
    }

    location ~ /\.
    {
    	deny all;
    }

    access_log /home/wwwlogs/www.log
}

```


## 使用方法
浏览器访问： https://www.guke1.com ，可以查看

在输入框输入查询的公交车，（如：快1）点击搜索后，会出现搜索到的车次，再次点击需要查询车次的方向，即可查看实时公交状态。

