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

            $table->string('nick_name')->nullable();
            $table->string('full_name')->nullable();
            $table->date('join_date');

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
