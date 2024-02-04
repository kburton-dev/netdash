<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;

class SqliteBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function handle(): void
    {
        $day = Date::now()->format('l');
        $filePath = config('database.connections.sqlite.database');

        if ($stream = fopen($filePath, 'r')) {
            Storage::disk('dropbox')
                ->put("/netdash/backup/{$day}-laravel.sql", $stream);
        }
    }
}
