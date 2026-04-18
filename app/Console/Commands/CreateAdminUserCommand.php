<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdminUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->ask('Email');
        $password = $this->secret('Password');
        User::query()->create([
            'name' => $email,
            'email' => $email,
            'password' => bcrypt($password),
            'is_admin' => true,
        ]);
        $this->info('User created');
    }
}
