<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            /**
             * Used for dashboard when no filtering is done, but we
             * are ordering on this column.
             */
            $table->index(['published_at']);

            /**
             * Used for dashboard when filtering is done for favourites.
             * It should filter the articles enough to not require also puting
             * title in this index.
             */
            $table->index(['favourited_at']);

            /**
             * Used for dashboard when filtering is done on article title.
             */
            $table->index(['feed_id', 'title']);
        });
    }
};
