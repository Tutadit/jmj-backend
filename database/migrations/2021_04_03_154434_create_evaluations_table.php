<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('answer');
            $table->timestamps();
            $table->unsignedBigInteger('review_id');   
            $table->string('metric_question');

            // 

            $table->foreign('review_id')->references('id')->on('reviews')->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('metric_question')->references('question')->on('metrics')->onUpdate('cascade')
            ->onDelete('cascade');
            $table->unique('metric_question');
            //
            $table->index(['review_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
}
