<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('assigned')->insert([
            'paper_id' => '1',
            'researcher_email' => 'researcher1@mail.com',
            'reviewer_email' => 'reviewer1@mail.com',
            'minor_rev_deadline' => '2020-01-02',
            'major_rev_deadline' => '2020-01-03'
        ]);
    }
}
