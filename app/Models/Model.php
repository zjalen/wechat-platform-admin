<?php
namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{

    protected static function boot()
    {
        parent::boot();
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    /**
     * 增加一个默认的排序
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function query(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::query()->orderByDesc('created_at');
    }
}
