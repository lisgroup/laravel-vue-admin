<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Article extends Model
{
    use Searchable;

    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    // protected $table = 'article';

    protected $fillable = ['category_id', 'title', 'author', 'description', 'markdown', 'content', 'keywords', 'cover_img', 'is_top', 'click'];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->only('id', 'title', 'keywords', 'description', 'markdown');
    }

    /**
     * 关联文章表
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 关联标签表
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tags');
    }
}
