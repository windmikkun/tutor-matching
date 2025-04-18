<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteLatestEmployerDuplicates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employers:delete-latest-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the latest duplicate employer records, keeping only the oldest per user_id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deleted = DB::delete(<<<SQL
DELETE FROM employers
WHERE id IN (
  SELECT id FROM (
    SELECT id,
           ROW_NUMBER() OVER (PARTITION BY user_id ORDER BY created_at DESC, id DESC) as rn
    FROM employers
  ) t
  WHERE t.rn > 1
)
SQL
        );
        $this->info("Deleted $deleted duplicate employer records.");
    }
}
