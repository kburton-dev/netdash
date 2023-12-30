<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feeds', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('url');
            $table->dateTime('last_fetch')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $expression = DB::connection()->getName() === 'sqlite'
                ? 'IIF(deleted_at IS NULL, url, NULL)'
                : 'IF(deleted_at IS NULL, url, NULL)';
            $table->string('unique_url')->virtualAs($expression)->unique();
        });
    }
};
