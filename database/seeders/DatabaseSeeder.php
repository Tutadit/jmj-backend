<?php

namespace Database\Seeders;

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
        $this->call([
            UserSeeder::class,
            AuthorsSeeder::class,
            EvaluationMetricSeeder::class,
            EvaluationSeeder::class,
            JournalSeeder::class,
            MeasuredBySeeder::class,
            MetricSeeder::class,
            NominatedReviewersSeeder::class,
            PaperSeeder::class,
            PaperJournalSeeder::class,            
            ReviewSeeder::class,
            AssignedsSeeder::class,
            
        ]);
    }
}
