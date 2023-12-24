<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feeds', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('type');
            $table->string('url')->unique();
            $table->dateTime('last_fetch')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }
};
