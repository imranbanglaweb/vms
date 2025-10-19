<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->index();
            $table->integer('unit_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('employee_id')->index()->nullable();
            $table->string('employee_name')->nullable();
            $table->text('designation')->nullable();
            $table->text('employee_description')->nullable();
            $table->text('gender')->nullable();
            $table->string('employee_email')->nullable();
            $table->integer('employee_phone')->nullable();
            $table->integer('employee_oder')->default(1);
            $table->string('remarks',250)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('ip_address')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
