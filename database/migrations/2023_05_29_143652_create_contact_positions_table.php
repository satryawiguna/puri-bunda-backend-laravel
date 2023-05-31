<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_positions', function (Blueprint $table) {
            $table->uuid('contact_id', 36);
            $table->unsignedBigInteger('position_id', 20);

            $table->nullableTimestamps();

            $table->unique(['contact_id', 'position_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_positions');
    }
}
