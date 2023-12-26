<?php

namespace App\Models;

use App\Feeds\FeedType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Article> $articles
 * @property-read int|null $articles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read string|null $hostname
 *
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
 *
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

    /**
     * @return HasMany<Article>
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * @return MorphToMany<Tag>
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * @return Attribute<string,string>
     */
    public function hostname(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                $fullHostName = parse_url($this->url, PHP_URL_HOST);

                if ($fullHostName === false || $fullHostName === null) {
                    return null;
                }

                return collect(explode('.', $fullHostName))
                    ->take(-2)
                    ->join('.');
            },
        );
    }
}
