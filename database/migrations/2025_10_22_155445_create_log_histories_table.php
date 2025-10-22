<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Who performed the action
            $table->string('action_type'); // e.g., create, update, approve, reject, delete
            $table->string('module_name'); // e.g., travel_requests, vehicles, drivers
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of the record affected
            $table->text('old_values')->nullable(); // JSON of old data (before change)
            $table->text('new_values')->nullable(); // JSON of new data (after change)
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->text('remarks')->nullable();

            // Common fields
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Relationship
            $table->foreign('user_id')->references('id')->on('employees')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_histories');
    }
}
