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
            'editor_comments' => 'This was very well written',
            'additional_comments' => 'I approve this',
            'reviewer_email' => 'reviewer@mail.com',
            'paper_id' => '1',
            'researcher_email' => 'researcher@mail.com',
            'status' => 'approved'
        ]);

        DB::table('reviews')->insert([
            'editor_comments' => 'This was NOT very well written',
            'additional_comments' => 'I reject this',
            'reviewer_email' => 'reviewer@mail.com',
            'paper_id' => '1',
            'researcher_email' => 'researcher@mail.com',
            'status' => 'rejected'
        ]);

        DB::table('reviews')->insert([
            'editor_comments' => 'I am gonna think about this one',
            'additional_comments' => 'Just wait',
            'reviewer_email' => 'reviewer@mail.com',
            'paper_id' => '1',
            'researcher_email' => 'researcher@mail.com',
            'status' => 'pending'
        ]);
    }
}
