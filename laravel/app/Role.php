<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $fillable = ['name'];

    // 用户和角色的模型关联关系
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // 角色和权限的模型关联关系
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    // 给角色添加权限
    public function givePermissionTo($permission)
    {
        return $this->permissions()->save($permission);
    }

    // 角色删除权限
    public function revokePermissionTo($permission)
    {
        return $this->permissions()->delete($permission);
    }
}
