<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Tester
 *
 * @property int $id
 * @property string $wechat_id 微信号
 * @property string $user_str 用户字符串
 * @property string $app_id app_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Tester newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tester newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tester query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tester whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tester whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tester whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tester whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tester whereUserStr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tester whereWechatId($value)
 * @mixin \Eloquent
 */
class Tester extends Model
{
    use HasFactory;
}
