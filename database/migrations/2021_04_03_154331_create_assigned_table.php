<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigned', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paper_id');              
            $table->string('researcher_email');
            $table->string('reviewer_email');
            $table->date('minor_rev_deadline');  
            $table->date('major_rev_deadline');        
            $table->timestamps();

            // foreign key
            $table->foreign('paper_id')->references('id')->on('papers');
            $table->foreign('researcher_email')->references('email')->on('users');
            $table->foreign('reviewer_email')->references('email')->on('users');

            // primary
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
        Schema::dropIfExists('assigneds');
    }
}
