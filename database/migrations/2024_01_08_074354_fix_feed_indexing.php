<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feeds', function (Blueprint $table): void {
            $table->dropIndex('feeds_unique_url_unique');

            $expression = DB::connection()->getName() === 'sqlite'
                ? "IIF(deleted_at IS NULL, COALESCE(user_id, 'NA') || '-' || url, NULL)"
                : "IF(deleted_at IS NULL, CONCAT(COALESCE(user_id, 'NA'), '-' url), NULL)";

            $table->string('user_id_url')->virtualAs($expression)->unique();
        });
    }
};
