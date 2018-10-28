## 安装方法：
为了方便自己使用，已经讲打包好的代码放到了 php/public 目录下。即正常部署时候，只需要配置后端 php 环境即可。

1. 安装 php 环境 (必须)
```php
git clone https://gitee.com/lisgroup/vueBus.git
cd vueBus/laravel
composer install
```

## 域名绑定
域名需要绑定到根目录，即项目的 php/public 目录下。

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

