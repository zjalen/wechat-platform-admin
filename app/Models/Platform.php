<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * App\Models\Platform
 *
 * @property int $id
 * @property string $name 名称
 * @property string $slug 标识
 * @property string|null $logo logo
 * @property string $app_id app_id
 * @property string $app_secret 密钥
 * @property string $token 校验 token
 * @property string $aes_key 解密 key
 * @property string|null $description 简介
 * @property bool $is_open 是否开放
 * @property int $type 类型
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $type_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Authorizer[] $subPlatforms
 * @property-read int|null $sub_platforms_count
 * @method static \Illuminate\Database\Eloquent\Builder|Platform newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Platform newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Platform query()
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereAesKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereAppSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereIsOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Platform extends Model
{
    use HasFactory;

    const TYPE_THIRD_PARTY = 0;
    const TYPE_OFFICIAL_ACCOUNT = 1;
    const TYPE_MINI_PROGRAM = 2;

    public static $typeMap = [
        self::TYPE_THIRD_PARTY   => '第三方平台',
        self::TYPE_OFFICIAL_ACCOUNT  => '公众号',
        self::TYPE_MINI_PROGRAM  => '小程序'
    ];

    /**
     * 强制转换的属性
     *
     * @var array
     */
    protected $casts = [
        'is_open' => 'boolean',
    ];

    protected $appends = ['type_name'];

    public function getTypeNameAttribute(): string
    {
        return self::$typeMap[$this->type];
    }

    /**
     * 开放平台第三方平台的关联绑定账号
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function localAuthorizers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Authorizer::class, 'platform_id', 'id');
    }
}
