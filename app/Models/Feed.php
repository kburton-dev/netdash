<?php

namespace App\Models;

use App\Feeds\FeedType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Feed
 *
 * @property int $id
 * @property FeedType $type
 * @property string $title
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $last_fetch
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Feed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feed query()
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereLastFetch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereType($value)
 * @mixin \Eloquent
 */
class Feed extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'last_fetch',
        'type',
    ];

    protected $casts = [
        'type' => FeedType::class,
        'last_fetch' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
