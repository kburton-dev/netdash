<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Builder;

class Favourites extends ArticleListingPage
{
    protected function getBaseArticleQuery(): Builder
    {
        return parent::getBaseArticleQuery()
            ->whereNotNull('favourited_at');
    }
}
