<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Entry;

class FixEmployerIdCommand extends Command
{
    protected $signature = 'fix:employer_id';
    protected $description = 'entriesテーブルのemployer_idをemployer1@example.comのidに修正';

    public function handle()
    {
        $userId = User::where('email', 'employer1@example.com')->value('id');
        $employerId = \App\Models\Employer::where('user_id', $userId)->value('id');
        if ($employerId) {
            Entry::query()->update(['employer_id' => $employerId]);
            $this->info("Updated entries.employer_id to $employerId");
        } else {
            $this->error('employer1@example.com not found in employers table');
        }
    }
}
