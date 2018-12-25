<?php

namespace App\Models\Traits;

use Vinkla\Hashids\Facades\Hashids;

trait HashIdHelper
{
    private $hashId;

    /**
     * 调用 $model->hashId 时触发
     *
     * @return string
     */
    public function getHashIdAttribute()
    {
        if (!$this->hashId) {
            $this->hashId = Hashids::encode($this->id);
        }

        return $this->hashId;
    }

    /**
     * 先将参数 decode 为模型 id，再调用父类的 resolveRouteBinding 方法
     *
     * @param $value
     *
     * @return \Illuminate\Database\Eloquent\Model|void|null
     */
    public function resolveRouteBinding($value)
    {
        if (!is_numeric($value)) {
            $value = current(Hashids::decode($value));
            if (!$value) {
                return;
            }
        }
        return parent::resolveRouteBinding($value);
    }

    /**
     * 使用 hash_id 生成 URL
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->hash_id;
    }
}
