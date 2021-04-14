<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->int('id');  
            $table->string('editor_comments');
            $table->string('additional_comments');
            $table->string('reviewer_email');
            $table->int('paper_id');
            $table->string('researcher_email');
            $table->timestamps();

            //
            $table->foreign('reviewer_email')->references('email')->on('users');
            $table->foreign('paper_id')->references('id')->on('papers');
            $table->foreign('researcher_email')->references('email')->on('users');

            //
            $table->index(['id', 'reviewer_email']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}