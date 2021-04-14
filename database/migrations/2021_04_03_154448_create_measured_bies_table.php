<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasuredBiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measured_bies', function (Blueprint $table) {
            $table->id();
            $table->string('em_name');
            $table->string('metric_question');
            $table->timestamps();

            //
            $table->foreign('metric_question')->references('metric_question')->on('evaluations')->onUpdate('cascade')
            ->onDelete('cascade'); 
            //TODO: check this is the foreign key

            // primary
            $table->index(['em_name', 'metric_question']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measured_bies');
    }
}
