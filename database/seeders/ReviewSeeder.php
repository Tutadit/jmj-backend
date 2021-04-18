<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reviews')->insert([
            'editor_comments' => 'What up',
            'additional_comments' => 'nigga',
            'reviewer_email' => 'reviewer@mail.com',
            'paper_id' => '1',
            'researcher_email' => 'researcher@mail.com',
            'status' => 'approved'
        ]);

        DB::table('reviews')->insert([
            'editor_comments' => 'What up',
            'additional_comments' => 'Artem',
            'reviewer_email' => 'reviewer@mail.com',
            'paper_id' => '1',
            'researcher_email' => 'researcher@mail.com',
            'status' => 'rejected'
        ]);
    }
}
