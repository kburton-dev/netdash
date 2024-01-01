<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feeds', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained();
        });

        if ($user = User::query()->first()) {
            DB::table('feeds')->update(['user_id' => $user->id]);
        }
    }
};
