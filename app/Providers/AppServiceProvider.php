<?php

namespace App\Providers;

use App\Feeds\AtomParser;
use App\Feeds\RssParser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->tag([RssParser::class, AtomParser::class], 'feedParsers');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventSilentlyDiscardingAttributes();
    }
}
