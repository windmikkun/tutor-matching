<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employer;

class ListEmployerEmails extends Command
{
    protected $signature = 'employers:emails';
    protected $description = 'List all employer user emails';

    public function handle()
    {
        $emails = Employer::with('user')->get()->map(function($e) {
            return $e->user->email ?? 'なし';
        });
        foreach ($emails as $email) {
            $this->line($email);
        }
        return 0;
    }
}
