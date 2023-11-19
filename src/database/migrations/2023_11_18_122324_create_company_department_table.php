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
            $table->unsignedBigInteger('departments_id');
            $table->unsignedBigInteger('companies_id');
            $table->timestamps();
            $table->foreign('departments_id')->references('id')->on('departments');
            $table->foreign('companies_id')->references('id')->on('companies');
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
