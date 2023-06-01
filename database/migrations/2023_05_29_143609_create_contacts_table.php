<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->uuid('id', 36)->primary();

            $table->uuidMorphs('contactable');

            $table->enum('type', ['EMPLOYEE', 'PATIENT']);

            $table->unsignedSmallInteger('unit_id')
                ->nullable();
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('restrict');

            $table->string('nick_name');
            $table->string('full_name');
            $table->date('join_date')->nullable();

            $table->string('created_by');
            $table->string('updated_by')->nullable();

            $table->nullableTimestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
