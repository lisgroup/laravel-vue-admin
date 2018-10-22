## 安装方法：
为了方便自己使用，已经讲打包好的代码放到了 php/public 目录下。即正常部署时候，只需要配置后端 php 环境即可。

1. 安装 php 环境 (必须)
```php
git clone https://gitee.com/lisgroup/vueBus.git
cd vueBus/php
composer install
```

## 域名绑定
域名需要绑定到根目录，即项目的 php/public 目录下。

## 使用方法
浏览器访问绑定的域名即可查看

在输入框输入查询的公交车，（如：快1）点击搜索后，会出现搜索到的车次，再次点击需要查询车次的方向，即可查看实时公交状态。
