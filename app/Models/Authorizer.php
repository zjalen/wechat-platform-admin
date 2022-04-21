<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Authorizer
 *
 * @property int $id
 * @property string $nick_name 昵称
 * @property string $slug 索引标识
 * @property string|null $head_img 头像
 * @property string $app_id app_id
 * @property string $principal_name 主体名称
 * @property string $qrcode_url 二维码地址
 * @property string $user_name 原始 id
 * @property int $service_type_info 平台类型
 * @property int $is_mini_program 是否是小程序
 * @property int $platform_id 关联第三方平台 id
 * @property int $status 绑定状态 1 绑定成功 0 未绑定
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer whereStatus($value)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $service_type_name
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer whereHeadImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer whereIsMiniProgram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer whereNickName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer wherePrincipalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer whereQrcodeUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer whereServiceTypeInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer whereUserName($value)
 * @mixin \Eloquent
 * @property int $is_auto_reply_open 是否启用自动回复
 * @method static \Illuminate\Database\Eloquent\Builder|Authorizer whereIsAutoReplyOpen($value)
 */
class Authorizer extends Model
{
    use HasFactory;

    public static $serviceOaType = [
        0 => '订阅号',
        1 => '订阅号',
        2 => '服务号'
    ];
    public static $serviceMpType = [
        0 => '普通小程序',
        12 => '试用小程序',
        2 => '门店小程序',
        3 => '门店小程序',
        4 => '小游戏',
        10 => '小商店'
    ];

    protected $hidden = [
        'service_type_info'
    ];

    protected $fillable = [
        'nick_name',
        'head_img',
        'app_id',
        'principal_name',
        'qrcode_url',
        'user_name',
        'service_type_info',
        'is_mini_program'
    ];

    protected $casts = [
        'is_mini_program' => 'boolean',
        'status' => 'boolean',
        'is_auto_reply_open' => 'boolean',
    ];

    protected $appends = ['service_type_name'];

    public function getServiceTypeNameAttribute(): string
    {
        if ($this->is_mini_program) {
            return self::$serviceMpType[$this->service_type_info];
        }
        return self::$serviceOaType[$this->service_type_info];
    }
}
