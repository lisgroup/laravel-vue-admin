# 开发记录 

## 1. 一些基础命令
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
    DB::table('cron_tasks')->insert([
        [
            'cid' => '175ecd8d-c39d-4116-83ff-109b946d7cb4',
            'LineGuid' => '921f91ad-757e-49d6-86ae-8e5f205117be',
            'LineInfo' => '快线1号(星塘公交中心)',
            'start_at' => '05:00:00',
            'end_at' => '23:10:00',
        ],
        [
            'cid' => '175ecd8d-c39d-4116-83ff-109b946d7cb4',
            'LineGuid' => 'af9b209b-f99d-4184-af7d-e6ac105d8e7f',
            'LineInfo' => '快线1号(木渎公交换乘枢纽站)',
            'start_at' => '05:00:00',
            'end_at' => '23:10:00',
        ]
    ]);
}
```

运行填充；

```
php artisan db:seed --class=CronTasksTableSeeder
```

3. 添加路由

/routes/web.php

```
<?php
use App\Models\Cron;

Route::get('search', function () {
    // 为查看方便都转成数组
    dump(Cron::all()->toArray());
});
```

4. 定时任务和创建命令相关

启动定时任务

```shell
# 使用 crontab 的定时任务调用 php artisan 调度任务：
crontab -e

# 追加如下内容： 

* * * * * php /home/ubuntu/vueBus/laravel/artisan schedule:run >> /dev/null 2>&1

# 最后 ctrl + o 保存退出即可。
```


## 2. TODO 待完成工作

~~1. 查询的公交线路存入数据库保存。（目前保存在文件中）已完成~~


## 3. Laravel使用 iseed 扩展导出表数据

iseed地址： [https://github.com/orangehill/iseed](https://github.com/orangehill/iseed)

### 3.1 iseed 安装

```
composer require orangehill/iseed
```

### 3.2 使用方法

如生成 lines 表的 seeder 文件:

```
php artisan iseed lines
```

## 4. 开箱即用注册登录功能

```shell
# 快速生成认证所需要的路由和视图
php artisan make:auth

# 数据库迁移填充
# 回滚再重新迁移 migrate:refresh 命令来填充数据库。彻底重构数据库
php artisan migrate:refresh --seed

```
