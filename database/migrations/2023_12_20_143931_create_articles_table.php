<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('url')->unique();
            $table->text('content');
            $table->string('image')->nullable();
            $table->dateTime('published_at');
            $table->foreignId('feed_id')->constrained();

            $table->timestamps();
            $table->softDeletes();
        });
    }
};
