<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'alejandro.suau@gmail.com',
            'phone' => '+34 665 67 37 69',
            'address' => 'C/Cala Torta, nº 1, 2º 2ª'
        ]);
        User::create([
            'email' => 'a.s@habitissimo.com',
            'phone' => '+34 665 67 37 69',
            'address' => 'C/Cala Nain, nº 1, 2º 2ª'
        ]);
    }
}
