<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Jack',
            'last_name' => 'Jackson',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'type'=>'admin'
        ]);

        DB::table('users')->insert([
            'first_name' => 'Mike',
            'last_name' => 'Mikeson',
            'email' => 'researcher@mail.com',
            'admin_email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'type'=>'researcher'
        ]);

        DB::table('degrees')->insert([
            'title' => 'Clown',
            'institution' => 'Clown University',
            'received' => '02/02/02',
            'researcher_email' => 'researcher@mail.com'
        ]);

        DB::table('users')->insert([
            'first_name' => 'Peter',
            'last_name' => 'Peterson',
            'email' => 'editor@mail.com',
            'admin_email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'type'=>'editor'
        ]);

        DB::table('users')->insert([
            'first_name' => 'Steven',
            'last_name' => 'Stevenson',
            'email' => 'reviewer@mail.com',
            'admin_email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'type'=>'reviewer'
        ]);

        DB::table('users')->insert([
            'first_name' => 'Carl',
            'last_name' => 'Carlson',
            'email' => 'viewer@mail.com',
            'admin_email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'type'=>'viewer'
        ]);
    }
}
