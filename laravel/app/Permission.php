<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    // protected $table = 'permissions';

    protected $fillable = ['name', 'route'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
