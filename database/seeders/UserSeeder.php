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
            'type'=>'admin',
            'status' => 'approved'
        ]);

        DB::table('users')->insert([
            'first_name' => 'Mike',
            'last_name' => 'Mikeson',
            'email' => 'researcher@mail.com',
            'admin_email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'type'=>'researcher',
            'status' => 'approved'
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
            'type'=>'editor',
            'status' => 'approved'
        ]);

        DB::table('users')->insert([
            'first_name' => 'Steven',
            'last_name' => 'Stevenson',
            'email' => 'reviewer@mail.com',
            'admin_email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'type'=>'reviewer',
            'status' => 'approved'
        ]);

        DB::table('users')->insert([
            'first_name' => 'Carl',
            'last_name' => 'Carlson',
            'email' => 'viewer@mail.com',
            'admin_email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'type'=>'viewer',
            'status' => 'approved'
        ]);

        // create researchers
        DB::table('users')->insert([
            'first_name' => 'Researcher',
            'last_name' => '1',
            'email' => 'researcher1@mail.com',
            'admin_email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'type'=>'researcher',
            'status' => 'approved'
        ]);

        DB::table('degrees')->insert([
            'title' => 'Deg1',
            'institution' => 'University1',
            'received' => '02/02/02',
            'researcher_email' => 'researcher1@mail.com'
        ]);

        DB::table('degrees')->insert([
            'title' => 'Deg2',
            'institution' => 'University2',
            'received' => '02/02/02',
            'researcher_email' => 'researcher1@mail.com'
        ]);

        DB::table('users')->insert([
            'first_name' => 'Researcher',
            'last_name' => '2',
            'email' => 'researcher2@mail.com',
            'admin_email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'type'=>'researcher',
            'status' => 'awaiting'
        ]);

        DB::table('degrees')->insert([
            'title' => 'Deg1',
            'institution' => 'University1',
            'received' => '02/02/02',
            'researcher_email' => 'researcher2@mail.com'
        ]);

        // create reviewers
        DB::table('users')->insert([
            'first_name' => 'Reviewer',
            'last_name' => '1',
            'email' => 'reviewer1@mail.com',
            'admin_email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'type'=>'reviewer',
            'status' => 'approved'
        ]);

        DB::table('users')->insert([
            'first_name' => 'Reviewer',
            'last_name' => '2',
            'email' => 'reviewer2@mail.com',
            'admin_email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'type'=>'reviewer',
            'status' => 'awaiting'
        ]);

        DB::table('users')->insert([
            'first_name' => 'James',
            'last_name' => 'Coco',
            'email' => 'reviewer3@mail.com',
            'admin_email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'type'=>'reviewer',
            'status' => 'approved'
        ]);

        DB::table('users')->insert([
            'first_name' => 'Sarah',
            'last_name' => 'Sonz',
            'email' => 'reviewer4@mail.com',
            'admin_email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'type'=>'reviewer',
            'status' => 'approved'
        ]);

        DB::table('users')->insert([
            'first_name' => 'Emily',
            'last_name' => 'Stuarts',
            'email' => 'reviewer5@mail.com',
            'admin_email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'type'=>'reviewer',
            'status' => 'approved'
        ]);

        DB::table('users')->insert([
            'first_name' => 'Cody',
            'last_name' => 'Fleminton',
            'email' => 'reviewer6@mail.com',
            'admin_email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'type'=>'reviewer',
            'status' => 'approved'
        ]);
    }
}
