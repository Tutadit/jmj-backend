<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaperJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paper_journals', function (Blueprint $table) {
            $table->id();
            $table->int('paper_id');              
            $table->string('researcher_email');
            $table->string('journal_title');
            $table->date('published_date'); 
            $table->timestamps();

            //
            $table->foreign('paper_id')->references('id')->on('papers');
            $table->foreign('researcher_email')->references('email')->on('users');
            $table->foreign('journal_title')->references('title')->on('journals');
            $table->foreign('published_date')->references('published_date')->on('journals');

            //
            $table->index(['paper_id', 'researcher_email', 'journal_title', 'published_date']); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paper_journals');
    }
}