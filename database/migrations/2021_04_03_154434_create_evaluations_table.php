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
            $table->unsignedBigInteger('metric_id');

            // 

            $table->foreign('review_id')->references('id')->on('reviews')->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('metric_id')->references('id')->on('metrics')->onUpdate('cascade')
            ->onDelete('cascade');
            $table->unique('metric_id');
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
