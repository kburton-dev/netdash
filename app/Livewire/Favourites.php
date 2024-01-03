<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Builder;

class Favourites extends ArticleListingPage
{
    protected function articleQueryModified(Builder $builder): void
    {
        $builder->whereNotNull('favourited_at');
    }
}
