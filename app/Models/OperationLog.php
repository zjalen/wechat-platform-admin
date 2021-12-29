<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\OperationLog
 *
 * @property int $id
 * @property int|null $user_id 操作者 id
 * @property string $path 请求路径
 * @property string $method 请求方法
 * @property string|null $ip ip
 * @property string|null $params 请求参数
 * @property mixed|null $wx_result 微信回复的结果
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationLog whereWxResult($value)
 * @mixin \Eloquent
 */
class OperationLog extends Model
{
    use HasFactory;

    protected $casts = [
        'wx_result' => 'array',
        'params' => 'array',
    ];
}
