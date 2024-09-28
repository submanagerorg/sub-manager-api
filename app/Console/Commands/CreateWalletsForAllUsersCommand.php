<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Console\Command;

class CreateWalletsForAllUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallets:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create wallets for all users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            if ($user->wallet) {
                $this->info("User {$user->id} already has a wallet.");
                continue;
            }

            $user->wallet()->create();

            $this->info("Wallet created for User {$user->id}");
        }

        $this->info('Wallets created for all users.');
    }
}
