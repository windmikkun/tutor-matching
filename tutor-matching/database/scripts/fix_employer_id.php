<?php
// database/scripts/fix_employer_id.php

use Illuminate\Database\Capsule\Manager as DB;
require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class);

$userId = DB::table('users')->where('email', 'employer1@example.com')->value('id');
if ($userId) {
    DB::table('entries')->update(['employer_id' => $userId]);
    echo "Updated entries.employer_id to $userId\n";
} else {
    echo "employer1@example.com not found\n";
}
