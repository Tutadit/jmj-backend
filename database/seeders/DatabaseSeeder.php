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
            AssignedSeeder::class,
            AuthorsSeeder::class,
            EvaluationMetricSeeder::class,
            EvaluationSeeder::class,
            JournalSeeder::class,
            MeasuredBySeeder::class,
            MetricSeeder::class,
            NominatedReviewersSeeder::class,
            PaperJournalSeeder::class,
            PaperSeeder::class,
            ReviewSeeder::class,
            UserSeeder::class,
        ]);
    }
}
