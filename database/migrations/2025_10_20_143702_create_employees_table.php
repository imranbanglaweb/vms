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
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('department_id');
            $table->string('employee_code')->unique();
            $table->string('name');
            $table->string('emp_code')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->enum('employee_type', ['Permanent', 'Contract', 'Intern','Provition'])->nullable();
            $table->string('designation')->nullable();
            $table->string('blood_group', 10)->nullable();
            $table->string('nid', 50)->nullable();
            $table->string('photo')->nullable();
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->date('join_date')->nullable();

            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();

            $table->timestamps();

            // Relationships
            // $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
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
