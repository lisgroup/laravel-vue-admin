1. 直接 require laravel-scout-tntsearch-driver ;
```
composer require vanry/laravel-scout-tntsearch
```

2. `config/app.php` 添加 Provider ；
```
'providers' => [

    // ...

    /**
     * TNTSearch 全文搜索
     */
    Laravel\Scout\ScoutServiceProvider::class,
    Vanry\Scout\TNTSearchScoutServiceProvider::class,
],
```
3. 中文分词 require jieba-php
```
composer require fukuball/jieba-php
```
4. 发布配置项;
```
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```

5. `/config/scout.php` 配置项中增加 `tntsearch` ；

```
'tntsearch' => [
    'storage' => storage_path('indexes'), //必须有可写权限
    'fuzziness' => env('TNTSEARCH_FUZZINESS', false),
    'searchBoolean' => env('TNTSEARCH_BOOLEAN', false),
    'asYouType' => false,

    'fuzzy' => [
        'prefix_length' => 2,
        'max_expansions' => 50,
        'distance' => 2,
    ],

    'tokenizer' => [
        'driver' => env('TNTSEARCH_TOKENIZER', 'default'),

        'jieba' => [
            'dict' => 'small',
            //'user_dict' => resource_path('dicts/mydict.txt'), //自定义词典路径
        ],

        'analysis' => [
            'result_type' => 2,
            'unit_word' => true,
            'differ_max' => true,
        ],

        'scws' => [
            'charset' => 'utf-8',
            'dict' => '/usr/local/scws/etc/dict.utf8.xdb',
            'rule' => '/usr/local/scws/etc/rules.utf8.ini',
            'multi' => 1,
            'ignore' => true,
            'duality' => false,
        ],
    ],

    'stopwords' => [
        '的',
        '了',
        '而是',
    ],
],
```
6. `.env` 文件增加配置

```
SCOUT_DRIVER=tntsearch
TNTSEARCH_TOKENIZER=jieba
```
7. 模型中定义全文搜索 

/app/Models/Article.php
```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Article extends Model
{
    use Searchable;

    /**
     * 索引的字段
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->only('id', 'title', 'content');
    }
}
```
8. php 默认的 memory_limit 是 128M；

为了防止 PHP Fatal error: Allowed memory size of n bytes exhausted ；
咱给增加到 256M 以解决内存不够报错的问题;

/app/Providers/AppServiceProvider.php
```
public function boot()
{
    /**
     * 增加内存防止中文分词报错
     */
    ini_set('memory_limit', "256M");
}
```
9. 生成索引；
```
php artisan scout:import "App\Models\Article"
```
使用起来也相当简单；
只需要把要搜索的内容传给 search() 方法即可;

/routes/web.php
```
<?php

use App\Models\Article;

Route::get('search', function () {
    // 为查看方便都转成数组
    dump(Article::all()->toArray());
    dump(Article::search('功能齐全的搜索引擎')->get()->toArray());
});
```

成功的查出了数据；

10. 最后我们再测下修改数据后的同步索引；
/routes/web.php
```
<?php

use App\Models\Article;

Route::get('search', function () {
    // 为查看方便都转成数组
    dump(Article::all()->toArray());
    dump('下面搜索的是：功能齐全的搜索引擎');
    dump(Article::search('功能齐全的搜索引擎')->get()->toArray());
    dump('此处把content改为：让全文检索变的简单而强大');
    // 修改 content 测试索引是否会自动同步
    $first = Article::find(1);
    $first->content = '让全文检索变的简单而强大';
    $first->save();
    dump('下面搜索的是：功能齐全的搜索引擎');
    dump(Article::search('功能齐全的搜索引擎')->get()->toArray());
    dump('下面搜索的是：简单的检索');
    dump(Article::search('简单的检索')->get()->toArray());
});
```
