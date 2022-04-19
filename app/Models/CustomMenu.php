<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\CustomMenu
 *
 * @property int $id
 * @property string $app_id appid
 * @property mixed $content 菜单内容
 * @property string $remark 备注
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMenu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMenu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMenu query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMenu whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMenu whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMenu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMenu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMenu whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomMenu whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomMenu extends Model
{
    use HasFactory;

    protected $casts = [
        'content' => 'array',
    ];
}
