<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNominatedReviewersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominated_reviewers', function (Blueprint $table) {
            $table->id();                       
            $table->int('paper_id');              
            $table->string('researcher_email');
            $table->string('reviewer_email');
            $table->timestamps();

            // foreign key
            $table->foreign('paper_id')->references('id')->on('papers');
            $table->foreign('researcher_email')->references('email')->on('users');
            $table->foreign('reviewer_email')->references('email')->on('users');

            // index
            $table->index(['paper_id', 'researcher_email', 'reviewer_email']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nominated_reviewers');
    }
}