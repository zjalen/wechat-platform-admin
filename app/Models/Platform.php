<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Platform
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $logo
 * @property string $app_id
 * @property string $app_secret
 * @property string $description
 * @property int $is_open
 * @property int $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Platform newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Platform newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Platform query()
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereAppSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereIsOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Platform extends Model
{
    use HasFactory;

    // 定义小区 2 种状态
    const TYPE_THIRD_PARTY = 0;
    const TYPE_OFFICIAL_ACCOUNT = 1;
    const TYPE_MINI_PROGRAM = 2;

    public static $typeMap = [
        self::TYPE_THIRD_PARTY   => '第三方平台',
        self::TYPE_OFFICIAL_ACCOUNT  => '公众号',
        self::TYPE_MINI_PROGRAM  => '小程序'
    ];

}
