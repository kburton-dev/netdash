<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Article
 *
 * @property int $id
 * @property string $title
 * @property string $url
 * @property string $content
 * @property string|null $image
 * @property \Illuminate\Support\Carbon $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $feed_id
 * @property string|null $favourited_at
 * @property-read \App\Models\Feed $feed
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereFeedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article wherePublishedAt($value)
 * @method static Builder|Article whereHasTags(array $tagIds)
 * @method static Builder|Article onlyTrashed()
 * @method static Builder|Article withTrashed()
 * @method static Builder|Article withoutTrashed()
 * @method static Builder|Article forUserId(int $userId)
 * @method static Builder|Article whereFavouritedAt($value)
 * @method static \Database\Factories\ArticleFactory factory($count = null, $state = [])*
 *
 * @mixin \Eloquent
 */
class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'url',
        'content',
        'image',
        'published_at',
        'feed_id',
        'favourited_at',
    ];

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'deleted_at' => 'datetime',
            'favourited_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Feed, self>
     */
    public function feed(): BelongsTo
    {
        return $this->belongsTo(Feed::class);
    }

    /**
     * @param  Builder<self>  $query
     * @param  list<int>  $tagIds
     * @return Builder<self>
     */
    public function scopeWhereHasTags(Builder $query, array $tagIds): Builder
    {
        if ($tagIds === []) {
            return $query->whereRaw('1 = 2');
        }

        return $query->whereHas('feed',
            fn (Builder $query) => $query->whereHas('tags',
                fn (Builder $query) => $query->whereIn('id', $tagIds)
            )
        );
    }

    /**
     * @param  Builder<self>  $query
     */
    public function scopeForUserId(Builder $query, int $userId): void
    {
        $query->whereHas('feed',
            fn (Builder $query) => $query->where('user_id', $userId)
        );
    }

    /**
     * @return Attribute<?string, ?string>
     */
    public function image(): Attribute
    {
        return Attribute::set(function (?string $value) {
            if ($value === null) {
                return null;
            }

            $host = parse_url($value, PHP_URL_HOST);

            if ($host !== null) {
                return $value;
            }

            $feedUrl = parse_url($this->feed->url);

            return "{$feedUrl['scheme']}://{$feedUrl['host']}{$value}"; // @phpstan-ignore-line - the URL should certainly have a schema and host at this point.
        });
    }
}
