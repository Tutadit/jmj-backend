<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('papers')->insert([
            'title' => 'Paper of number 1',
            'status' => 'published',
            'file_path' => 'paper1.pdf',
            'researcher_email' => 'researcher@mail.com',
            'editor_email' => 'editor@mail.com',
            'em_name' => 'Number Eval'
        ]);

        DB::table('papers')->insert([
            'title' => 'Paper of number 2',
            'status' => 'pending_minor_revision',
            'file_path' => 'paper1.pdf',
            'researcher_email' => 'researcher@mail.com',
            'editor_email' => 'editor@mail.com',
            'em_name' => 'Number Eval'
        ]);

        DB::table('papers')->insert([
            'title' => 'Paper of number 3',
            'status' => 'pending_major_revision',
            'file_path' => 'paper1.pdf',
            'researcher_email' => 'researcher@mail.com',
            'editor_email' => 'editor@mail.com',
            'em_name' => 'Number Eval'
        ]);

        DB::table('papers')->insert([
            'title' => 'Paper of number 4',
            'status' => 'pending_publication',
            'file_path' => 'paper1.pdf',
            'researcher_email' => 'researcher@mail.com',
            'editor_email' => 'editor@mail.com',
            'em_name' => 'Number Eval'
        ]);

        DB::table('papers')->insert([
            'title' => 'Paper of number 5',
            'status' => 'published',
            'file_path' => 'paper1.pdf',
            'researcher_email' => 'researcher@mail.com',
            'editor_email' => 'editor@mail.com',
            'em_name' => 'Number Eval'
        ]);

        DB::table('papers')->insert([
            'title' => 'Paper of number 6',
            'status' => 'published',
            'file_path' => 'paper1.pdf',
            'researcher_email' => 'researcher@mail.com',
            'editor_email' => 'editor@mail.com',
            'em_name' => 'Number Eval'
        ]);

        DB::table('papers')->insert([
            'title' => 'Paper of number 7',
            'status' => 'published',
            'file_path' => 'paper1.pdf',
            'researcher_email' => 'researcher@mail.com',
            'editor_email' => 'editor@mail.com',
            'em_name' => 'Number Eval'
        ]);

        DB::table('papers')->insert([
            'title' => 'Paper of number 8',
            'status' => 'published',
            'file_path' => 'paper1.pdf',
            'researcher_email' => 'researcher@mail.com',
            'editor_email' => 'editor@mail.com',
            'em_name' => 'Number Eval'
        ]);
    }
}
