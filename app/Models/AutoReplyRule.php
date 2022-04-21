<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\AutoReplyRule
 *
 * @property int $id
 * @property string $app_id appid
 * @property string $name 规则名
 * @property string $keyword 关键词
 * @property int $match_type 匹配方式
 * @property int $type 回复类型
 * @property mixed $content 回复内容
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AutoReplyRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AutoReplyRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AutoReplyRule query()
 * @method static \Illuminate\Database\Eloquent\Builder|AutoReplyRule whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoReplyRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoReplyRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoReplyRule whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoReplyRule whereMatchType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoReplyRule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoReplyRule whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoReplyRule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoReplyRule whereAppId($value)
 * @mixin \Eloquent
 */
class AutoReplyRule extends Model
{
    use HasFactory;

    protected $casts = [
        'content' => 'array',
    ];
}
