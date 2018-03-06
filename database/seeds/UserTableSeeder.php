<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'=>'Marzio Perez',
            'email'=>'marzioperez@gmail.com',
            'password'=>'123456',
            'type' => 'A'
        ]);


        User::create([
            'name'=>'Isaac Abensur',
            'email'=>'isaacabensur@gmail.com',
            'password'=>'123456',
            'type' => 'A'
        ]);
    }
}
