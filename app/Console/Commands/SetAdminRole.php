<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SetAdminRole extends Command
{
    protected $signature = 'user:set-admin';
    protected $description = 'Set all users to admin role';

    public function handle()
    {
        User::query()->update(['role' => 'admin']);
        $this->info('All users have been set to admin role.');
    }
}
