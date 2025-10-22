<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->foreignId('unit_id')->nullable()->constrained('units');
            $table->foreignId('department_id')->nullable()->constrained('departments');

            $table->string('vehicle_number', 100)->unique();
            $table->string('vehicle_type')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('model')->nullable();
            $table->string('color')->nullable();
            $table->date('registration_date')->nullable();
            $table->string('fuel_type', 50)->nullable();
            $table->string('engine_number', 100)->nullable();
            $table->string('chassis_number', 100)->nullable();
            $table->string('registration_certificate')->nullable();
            $table->enum('availability', ['Available', 'Assigned', 'Under Maintenance'])->default('Available');

            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
