<?php

namespace App\App\Advertisements\Domain\Models;

use App\App\AdvertisementOffers\Domain\Models\AdvertisementOffer;
use App\App\Advertisements\Domain\Scoping\Scoper;
use App\App\Categories\Domain\Models\Category;
use App\Generic\Domain\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'category_id',
        'preferably_swap_with'
    ];

    protected $with = ['category'];

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return "slug";
    }

    /**
     * @param Builder $builder
     * @param array $scopes
     * @return Builder
     */
    public function scopeWithScopes(Builder $builder, $scopes = [])
    {
        return (new Scoper(request()))->apply($builder, $scopes);
    }

    public function getMainImageAttribute($value)
    {
        return $value ?: 'https://cdn-ds.com/noimage/noimage.jpg';
    }

    public function getVideoPathAttribute($value)
    {
        return $value ?: 'https://www.youtube.com/watch?v=0I647GU3Jsc&list=WL&index=2';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function preferredCategoryToSwapWith()
    {
        return $this->belongsTo(Category::class, 'preferably_swap_with', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offers()
    {
        return $this->hasMany(AdvertisementOffer::class, 'provided_to', 'id');
    }

    public function ownedBy($user)
    {
        return $this->owner->is($user);
    }
}
