<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    const ADMIN_ID = 1; // 超级管理员 ID

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return mixed|string
     */
    public function generateToken()
    {
        $this->api_token = str_random(60);
        $this->save();

        return $this->api_token;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // 判断用户是否具有某个角色
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return !!$role->intersect($this->roles)->count();
    }

    // 判断用户是否具有某权限
    public function hasPermissionOld($permission)
    {
        return $this->hasRole($permission->roles);
    }

    /**
     * 封装一个方法方便使用
     * 1. 需要的权限
     * 2. 遍历当期那用户拥有的所有角色
     * 3. 再通过角色判断是否有当前需要的权限
     *
     * @param $permissionName
     *
     * @return bool
     */
    public function hasPermissionMy($permissionName)
    {
        foreach ($this->roles as $role) {
            $permissions = array_column($role->permissions->toArray(), 'name');
            return in_array($permissionName, $permissions);
            // if ($role->permisssions()->where('name', $permissionName)->exists()) {
            //     return true;
            // }
        }

        return false;
    }

    /**
     * @param $permissions []
     * @param $option [] valid_all 是否判断全部权限
     *
     * @return array|bool|null
     */
    function hasPermission($permissions, $option = [])
    {
        $option = array_merge(['valid_all' => false,], $option);
        if (!is_array($permissions)) $permissions = [$permissions];
        $gates = cacheUserRolesAndPermissions(\Auth::id(), false);

        foreach ($permissions as $permission) {
            if (in_array($permission, $gates['permissions'])) {
                if (!$option['valid_all']) {
                    return true;
                }
            } else {
                if ($option['valid_all']) {
                    return false;
                }
            }
        }
        if ($option['valid_all'])
            return true;
        else
            return false;
    }

    // 给用户分配角色
    public function assignRole($role)
    {
        return $this->roles()->save(
            Role::whereName($role)->firstOrFail()
        );
    }
}
