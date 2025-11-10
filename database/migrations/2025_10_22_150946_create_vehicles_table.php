<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_name', 100);
            $table->unsignedBigInteger('department_id')->nullable();
            $table->date('registration_date')->nullable();
            $table->string('license_plate', 50)->unique();
            $table->string('alert_cell_number', 20)->nullable();
            $table->enum('ownership', ['Owned', 'Rented', 'Leased'])->nullable();
            $table->unsignedBigInteger('vehicle_type_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedInteger('seat_capacity')->nullable();

            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Foreign keys
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('vehicle_type_id')->references('id')->on('vehicle_types');
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('vendor_id')->references('id')->on('vendors');
        });
    }

    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['vehicle_type_id']);
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['vendor_id']);
        });
        Schema::dropIfExists('vehicles');
    }
}
