<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use App\Models\UsersHasAccount;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'      => 'Filipe Monteiro',
            'email'     => 'filipe.monteiro@picpay.com.br',
            'password'  => bcrypt('123456'),
            'document'  => '21662738072'
        ]);

        User::create([
            'name'      => 'Filipe Eduardo',
            'email'     => 'filipe.eduardo@picpay.com.br',
            'password'  => bcrypt('123456'),
            'document'  => '88665909087'
        ]);

        Account::create([
            'type'          => 'Digital',
            'balance'       => 100,
            'is_default'    => true
        ]);

        Account::create([
            'type'          => 'Digital',
            'balance'       => 100,
            'is_default'    => true
        ]);

        UsersHasAccount::create([
            'user_id'       => 1,
            'account_id'    => 1
        ]);

        UsersHasAccount::create([
            'user_id'       => 2,
            'account_id'    => 2
        ]);
    }
}
