<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('company_department', function (Blueprint $table) {
            $table->id(); // auto increment id
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('company_id');
            $table->timestamps();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete("cascade");
            $table->foreign('company_id')->references('id')->on('companies')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_department');
    }
};
